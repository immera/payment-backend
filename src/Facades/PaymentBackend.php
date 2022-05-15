<?php

namespace Adiechahk\PaymentBackend\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentBackend extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'payment-backend';
    }
}
