<?php

namespace Immera\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'payment';
    }
}
