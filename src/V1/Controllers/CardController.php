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
            // Use old method as it is  
            return parent::create($request);
        }
        
    }
}