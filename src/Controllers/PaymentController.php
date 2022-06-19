<?php

namespace Immera\Payment\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Immera\Payment\Events\PaymentInstanceCreated;
use Immera\Payment\Events\PaymentInstanceUpdated;
use Immera\Payment\Models\PaymentInstance;
use Immera\Payment\Payment;

class PaymentController extends Controller
{
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
        $pay_instance->response_object = $response;
        $pay_instance->save();

        // event(new PaymentInstanceCreated($pay_instance));

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
        // $payload = json_decode(@file_get_contents('php://input'), true);
        // $code = Payment::handleWebhook($payload, $_SERVER['HTTP_STRIPE_SIGNATURE']);
        // http_response_code($code);

        // #######################################################
        // $endpoint_secret = config('service.stripe')['webhook_secret'];
        // $payload = @file_get_contents('php://input');

        
        // $event = null;

        // try {
        //     $event = \Stripe\Event::constructFrom(
        //         json_decode($payload, true)
        //     );
        // } catch (\UnexpectedValueException $e) {
        //     // Invalid payload
        //     Log::info('UnexpectedValueException' . $e->getMessage());
        //     echo '⚠️  Webhook error while parsing basic request.';
        //     http_response_code(400);
        //     exit();
        // }
        // Only verify the event if there is an endpoint secret defined
        // Otherwise use the basic decoded event
        // $sig_header = ;
        // try {
        //     $event = \Stripe\Webhook::constructEvent(
        //         $payload, $sig_header, $endpoint_secret
        //     );
        //     // Log::info('event-alipay' . $event);
        // } catch (\Stripe\Exception\SignatureVerificationException $e) {
        //     // Invalid signature
        //     Log::info('SignatureVerificationException' . $e->getMessage());
        //     echo '⚠️  Webhook error while validating signature.';
        //     http_response_code(400);
        //     exit();
        // }

        // // Handle the event
        // switch ($event->type) {
        //     case 'customer.created':
        //         Log::info('customer.created' . $event->data);
        //     case 'charge.succeeded':
        //         $event->data->object;
        //         $object = $event->data->object;
        //         if(isset($object->metadata->order_id)) {
        //             Order::where(['id' => $object->metadata->order_id])
        //                 ->update([
        //                     'payment_method' => $object->payment_method_details->type,
        //                     'payment_status' => config('service.payment_status')['Success'],
        //                     'transaction_id' => $object->id ?? '',
        //                 ]);
        //         } else {
        //             $object = $event->data->object;
        //             Log::info('object of subscription' . $object);
        //             $subscriptionUser = SubscriptionUser::where('id', $object->metadata->subscription_user_id)->with('subscription')->first();
        //             $updateData = [
        //                 'payment_method' => $object->payment_method_details->type,
        //                 'payment_status' => config('service.payment_status')['Success'],
        //                 'transaction_id' => $object->id ?? '',
        //             ];
        //             $subscriptionUser->fill($updateData)->save();
        //             Mail::to(Auth::user()->email)->locale(app()->getLocale())
        //                 ->queue(new TrainingSubscription($subscriptionUser));
        //         }

        //         Log::info('charge.succeeded' . $event->data);
        //     default:
        //         echo 'Received unknown event type ' . $event->type;
        // }
        // http_response_code(200);
    }
}
