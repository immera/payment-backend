<?php

namespace Immera\Payment\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Immera\Payment\Events\PaymentInstanceCreated;
use Immera\Payment\Events\PaymentInstanceUpdated;
use Immera\Payment\Models\PaymentInstance;
use Immera\Payment\Payment;
use Log;

class PaymentController extends Controller
{
    public function enabledMethods($slug = 'default') {
        $methods = config('payment.payment_methods');
        return Arr::get($methods, $slug, []);
    }

    public function index(Request $request)
    {
        return PaymentInstance::where($request->all())->get();
    }

    public function ack(Request $request, PaymentInstance $paymentInstance)
    {
        try {
            $paymentInstance->status = 'successful';
            $paymentInstance->save();

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false];
        }
    }

    public function initPayment(Request $request)
    {
        $pay_instance = new PaymentInstance();
        $pay_instance->payment_method = $request->payment_method;
        $pay_instance->return_url = $request->return_url;
        $pay_instance->amount = $request->amount;
        $pay_instance->currency = $request->currency;
        $pay_instance->additional_info = $request->additional_info;
        $pay_instance->save();

        $payment = new Payment();

        $options = $request->except(['currency', 'amount', 'payment_method']);

        $response = $payment->pay(
            $request->payment_method,
            $request->currency,
            $request->amount,
            $options
        );

        $pay_instance->refresh();
        $pay_instance->setIntentIdFromObj($response, ['id']);
        $pay_instance->setClientSecretFromObj($response);
        $pay_instance->setStatusFromObj($response);
        $pay_instance->request_options = $request->all();
        $pay_instance->response_object = json_encode($response);
        $pay_instance->save();

        event(new PaymentInstanceCreated($pay_instance));

        return [
            'callback' => route('payment.callback'),
            'response' => $response,
        ];
    }

    public function callback(Request $request)
    {
        $pay_instance = Payment::updateStatus(
            PaymentInstance::getFromID($request->payment_intent),
            $request->redirect_status
        );
        if ($pay_instance) {
            if ($request->dont_redirect) {
                return "Successfully updated to " . $request->redirect_status;
            }
            return redirect($pay_instance->return_url);
        }
        return 'Payment Instance not found !';
    }

    public function webhook(Request $request)
    {
        Log::info("Recieved event on the webhook endpoint.");
        $endpoint_secret = config('payment.stripe.webhook_secret');
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $event = null;
        
        try {   
          $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
          );
        } catch(\UnexpectedValueException $e) {
          // Invalid payload
          Log::error("Error while creting an event object [invalid payload]: " . $e->getMessage());
          http_response_code(400);
          exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
          // Invalid signature
          Log::error("Error while creting an event object [invalid signature]: " . $e->getMessage());
          http_response_code(400);
          exit();
        }

        $pay_object = $event->data->object;
        switch($event->type)
        {
            case 'payment_intent.succeeded':
            case 'source.chargeable':
                $pay_instance = Payment::updateStatus(
                    PaymentInstance::getFromID($request->payment_intent),
                    'SUCCESS'
                );
                if ($pay_instance) {
                    Log::info("Payment instance with intent id " . $pay_object->id . " has been paid successfully.");
                } else {
                    Log::info("Payment instance not found.");
                }
                break;
            case 'payment_intent.partially_funded':
                $pay_instance = Payment::updateStatus(
                    PaymentInstance::getFromID($request->payment_intent),
                    'PARTIAL'
                );
                if ($pay_instance) {
                    Log::info("Payment instance with intent id " . $pay_object->id . " has been paid partially.");
                } else {
                    Log::info("Payment instance not found.");
                }
                break;
            case 'source.failed':
            case 'source.canceled':	
                $pay_instance = Payment::updateStatus(
                    PaymentInstance::getFromID($request->payment_intent),
                    'FAILED'
                );
                if ($pay_instance) {
                    Log::info("Payment instance with intent id " . $pay_object->id . " has been paid partially.");
                } else {
                    Log::info("Payment instance not found.");
                }
                break;
        }
    }

    public function fundingInstructions()
    {
        return (new Payment)->fundingInstructions();
    }
}
