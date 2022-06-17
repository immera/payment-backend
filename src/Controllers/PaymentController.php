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
        return 'Yet to implement !';
    }
}
