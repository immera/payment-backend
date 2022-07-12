<?php

namespace Immera\Payment\Events;

use Immera\Payment\Models\PaymentInstance;

interface PaymentEventInterface {
    public function getPaymentInstace(): PaymentInstance ;
}