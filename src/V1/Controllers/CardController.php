<?php

namespace Immera\Payment\V1\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Immera\Payment\Events\PaymentInstanceCreated;
use Immera\Payment\Events\PaymentInstanceUpdated;
use Immera\Payment\Models\PaymentInstance;
use Immera\Payment\Payment;
use Immera\Payment\Controllers\CardController as CardControllerDefault;

class CardController extends CardControllerDefault
{
    
}