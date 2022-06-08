<?php

namespace Immera\Payment\Observers;

use Immera\Payment\Models\PaymentInstance;

class PaymentInstanceObserver
{
    /**
     * Handle the PaymentInstance "created" event.
     *
     * @return void
     */
    public function created(PaymentInstance $paymentInstance)
    {
    }

    /**
     * Handle the PaymentInstance "updated" event.
     *
     * @return void
     */
    public function updated(PaymentInstance $paymentInstance)
    {
    }

    /**
     * Handle the PaymentInstance "deleted" event.
     *
     * @return void
     */
    public function deleted(PaymentInstance $paymentInstance)
    {
    }

    /**
     * Handle the PaymentInstance "restored" event.
     *
     * @return void
     */
    public function restored(PaymentInstance $paymentInstance)
    {
    }

    /**
     * Handle the PaymentInstance "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(PaymentInstance $paymentInstance)
    {
    }
}
