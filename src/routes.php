<?php

use Illuminate\Support\Facades\Route;
use Immera\Payment\Controllers\PaymentController;

if (app() instanceof \Illuminate\Foundation\Application) {
    Route::withoutMiddleware([
        \App\Http\Middleware\VerifyCsrfToken::class,
    ])->group(['prefix' => config('payment.route_prefix', '')], function () {
        Route::get('/payment/instances', [PaymentController::class, 'index'])->name('payment.get');
        Route::patch('/payment/instances/{paymentInstance}/ack', [PaymentController::class, 'ack'])->name('payment.ack');
        Route::post('/payment/request', [PaymentController::class, 'initPayment'])->name('payment.init');
        Route::any('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
        Route::any('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
    });
} else {
    $r = $this->app->router;
    $r->group(['prefix' => config('payment.route_prefix', '')], function () use ($r) {
        $r->get(
            '/payment/instances', [
                'as' => 'payment.get',
                'uses' => 'Immera\Payment\Controllers\PaymentController@index',
            ]
        );
        $r->patch(
            '/payment/instances/{paymentInstance}/ack', [
                'as' => 'payment.ack',
                'uses' => 'Immera\Payment\Controllers\PaymentController@ack',
            ]
        );
        $r->post(
            '/payment/request', [
                'as' => 'payment.init',
                'uses' => 'Immera\Payment\Controllers\PaymentController@initPayment',
            ]
        );
        $r->get(
            '/payment/callback', [
                'as' => 'payment.callback',
                'uses' => 'Immera\Payment\Controllers\PaymentController@callback',
            ]
        );
        $r->post(
            '/payment/callback', [
                'as' => 'payment.callback',
                'uses' => 'Immera\Payment\Controllers\PaymentController@callback',
            ]
        );
        $r->get(
            '/payment/webhook', [
                'as' => 'payment.webhook',
                'uses' => 'Immera\Payment\Controllers\PaymentController@webhook',
            ]
        );
        $r->post(
            '/payment/webhook', [
                'as' => 'payment.webhook',
                'uses' => 'Immera\Payment\Controllers\PaymentController@webhook',
            ]
        );
    });
}
