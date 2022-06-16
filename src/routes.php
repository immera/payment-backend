<?php

use Illuminate\Support\Facades\Route;
use Immera\Payment\Controllers\PaymentController;
use Immera\Payment\Controllers\CardController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Auth;

Route::get('hello', function() {
    return request()->except(['amount']);
});

if (app() instanceof \Illuminate\Foundation\Application) {
    Route::withoutMiddleware([
        VerifyCsrfToken::class,
    ])->prefix(config('payment.route_prefix', '/api'))->group(function () {
        Route::get('/payment/instances', [PaymentController::class, 'index'])->name('payment.get');
        Route::patch('/payment/instances/{paymentInstance}/ack', [PaymentController::class, 'ack'])->name('payment.ack');
        Route::post('/payment/request', [PaymentController::class, 'initPayment'])->name('payment.init');
        Route::any('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
        Route::any('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
        Route::post('/payment/cards', [CardController::class, 'create'])->name('card.create');
        Route::get('/payment/cards', [CardController::class, 'index'])->name('card.list');
    });
} else {
    $r = $this->app->router;
    $r->group(['prefix' => config('payment.route_prefix', '/api')], function () use ($r) {
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
        $r->post(
            '/payment/cards', [
                'as' => 'card.create',
                'uses' => 'Immera\Payment\Controllers\CardController@create',
            ]
        );
        $r->get(
            '/payment/cards', [
                'as' => 'card.list',
                'uses' => 'Immera\Payment\Controllers\CardController@index',
            ]
        );
    });
}
