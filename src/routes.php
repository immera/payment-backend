<?php

use Adiechahk\PaymentBackend\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

if (app() instanceof \Illuminate\Foundation\Application) {
    Route::withoutMiddleware([
        \App\Http\Middleware\VerifyCsrfToken::class
    ])->group(function() {
        Route::get(
            '/payment/instances',
            [PaymentController::class, 'index']
        )->name('payment.get');
        Route::patch(
            '/payment/instances/{paymentInstance}/ack',
            [PaymentController::class, 'ack']
        )->name('payment.ack');
        Route::post(
            '/payment/request',
            [PaymentController::class, 'initPayment']
        )->name('payment.init');
        Route::any(
            '/payment/callback',
            [PaymentController::class, 'callback']
        )->name('payment.callback');
        Route::any(
            '/payment/webhook',
            [PaymentController::class, 'webhook']
        )->name('payment.webhook');
    });
} else {
    $this->app->router->get(
        '/payment/instances',
        ["as" => 'payment.get', 'PaymentController@index']
    );
    $this->app->router->patch(
        '/payment/instances/{paymentInstance}/ack',
        ["as" => 'payment.ack', 'PaymentController@ack']
    );
    $this->app->router->post(
        '/payment/request',
        ["as" => 'payment.init', 'PaymentController@initPayment']
    );
    $this->app->router->get(
        '/payment/callback',
        ["as" => 'payment.callback', 'PaymentController@callback']
    );
    $this->app->router->post(
        '/payment/callback',
        ["as" => 'payment.callback', 'PaymentController@callback']
    );
    $this->app->router->get(
        '/payment/webhook',
        ["as" => 'payment.webhook', 'PaymentController@webhook']
    );    
    $this->app->router->post(
        '/payment/webhook',
        ["as" => 'payment.webhook', 'PaymentController@webhook']
    );    
}



