<?php

use Illuminate\Support\Facades\Route;
use Immera\Payment\Controllers\PaymentController;
use Immera\Payment\Controllers\CardController;
use Immera\Payment\Controllers\PaypalController;
use App\Http\Middleware\VerifyCsrfToken;
use Immera\Payment\PaymentRouter;
use Immera\Payment\V1\PaymentRouter as PaymentRouterV1;

$routerCls = config('payment.active_version') === 'v1' ? PaymentRouterV1::class: PaymentRouter::class;

if (app() instanceof \Illuminate\Foundation\Application) {
    $routerCls::laravel();
} else {
    $routerCls::lumen($this->app->router);
} 