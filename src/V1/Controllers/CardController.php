<?php

namespace Immera\Payment\V1\Controllers;

use Illuminate\Http\Request;
use Immera\Payment\Controllers\CardController as CardControllerDefault;
use Immera\Payment\V1\Payment;

class CardController extends CardControllerDefault
{
    public function create(Request $request)
    {   
        if(isset($request->token)){
            // Token for creating card on stripe
            $card = [
                'token' => $request->token['id']
            ];
           
            // Creating payment object and card for that.
            $payment = new Payment();
            return $payment->createCardByToken($card);            
        }else{
           
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
}