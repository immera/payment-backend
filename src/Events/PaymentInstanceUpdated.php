<?php

namespace Immera\Payment\Events;

use Illuminate\Queue\SerializesModels;
use Immera\Payment\Models\PaymentInstance;

class PaymentInstanceUpdated implements PaymentEventInterface
{
    use SerializesModels;

    public $payment_instance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaymentInstance $pi)
    {
        $this->payment_instance = $pi;
    }

    public function getPaymentInstace(): PaymentInstance
    {
        return $this->payment_instance;
    }

}
