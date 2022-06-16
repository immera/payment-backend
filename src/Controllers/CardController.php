<?php

namespace Immera\Payment\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Immera\Payment\Events\PaymentInstanceCreated;
use Immera\Payment\Events\PaymentInstanceUpdated;
use Immera\Payment\Models\PaymentInstance;
use Immera\Payment\Payment;

class CardController extends Controller
{
    public function index(Request $request)
    {
        // Creating payment object and card for that.
        $payment = new Payment();
        return $payment->getCards();
    }

    public function create(Request $request)
    {
        // Split month and year from expiry
        $monthYearArray = explode('/', $request->expiry_date);

        // Generate Token for creating card on stripe
        $card = [
            'number' => $request->number,
            'exp_month' => $monthYearArray[0],
            'exp_year' => $monthYearArray[1],
            'cvc' => $request->cvc,
        ];

        // Creating payment object and card for that.
        $payment = new Payment();
        return $payment->createCard($card);
    }

}
