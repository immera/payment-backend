<?php

namespace Adiechahk\PaymentBackend\Observers;

use Adiechahk\PaymentBackend\Models\PaymentInstance;

class PaymentInstanceObserver
{
    /**
     * Handle the PaymentInstance "created" event.
     *
     * @param  \App\Models\PaymentInstance  $paymentInstance
     * @return void
     */
    public function created(PaymentInstance $paymentInstance)
    {
        //
    }

    /**
     * Handle the PaymentInstance "updated" event.
     *
     * @param  \App\Models\PaymentInstance  $paymentInstance
     * @return void
     */
    public function updated(PaymentInstance $paymentInstance)
    {
        //
    }

    /**
     * Handle the PaymentInstance "deleted" event.
     *
     * @param  \App\Models\PaymentInstance  $paymentInstance
     * @return void
     */
    public function deleted(PaymentInstance $paymentInstance)
    {
        //
    }

    /**
     * Handle the PaymentInstance "restored" event.
     *
     * @param  \App\Models\PaymentInstance  $paymentInstance
     * @return void
     */
    public function restored(PaymentInstance $paymentInstance)
    {
        //
    }

    /**
     * Handle the PaymentInstance "force deleted" event.
     *
     * @param  \App\Models\PaymentInstance  $paymentInstance
     * @return void
     */
    public function forceDeleted(PaymentInstance $paymentInstance)
    {
        //
    }
}
