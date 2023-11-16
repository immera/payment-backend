<?php

namespace Immera\Payment\V1\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Immera\Payment\Events\PaymentInstanceCreated;
use Immera\Payment\Events\PaymentInstanceUpdated;
use Immera\Payment\Models\PaymentInstance;
use Immera\Payment\Payment;

class PaypalController extends Controller
{
    public function captureOrder(Request $request, $order)
    {
        Payment::updateStatus(
            PaymentInstance::getFromID($order),
            "SUCCESS"
        );
        $payment = new Payment();
        return $payment->paypal()->captureOrder($order)->json();
    }
}
