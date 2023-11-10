<?php

use Illuminate\Support\Facades\Route;
use Immera\Payment\Controllers\PaymentController;
use Immera\Payment\Controllers\CardController;
use Immera\Payment\Controllers\PaypalController;
use App\Http\Middleware\VerifyCsrfToken;
use Immera\Payment\Controllers\Version2\PaymentVersion2Controller;


if (app() instanceof \Illuminate\Foundation\Application) {
    // This will be execute when Laravel Application is there

    if(config('payment.active_version') === 'v2') {
        // New code 
        Route::withoutMiddleware([
            VerifyCsrfToken::class,
        ])
        ->prefix(config('payment.route_prefix', '/api') . '/v2')
        ->middleware(config('payment.middlewares', []))
        ->group(function () {
            Route::get('/payment/instances', [PaymentController::class, 'index'])->name('payment.get');
            Route::patch('/payment/instances/{paymentInstance}/ack', [PaymentController::class, 'ack'])->name('payment.ack');
            Route::post('/payment/request', [PaymentVersion2Controller::class, 'initPayment'])->name('payment.init');
            Route::post('/payment/paypal/order/{order}/capture', [PaypalController::class, 'captureOrder'])->name('payment.paypalOrderCapture');
            Route::any('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
            Route::any('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
            Route::delete('/payment/cards/{card}', [CardController::class, 'delete'])->name('card.delete');
            Route::post('/payment/cards', [CardController::class, 'create'])->name('card.create');
            Route::get('/payment/cards', [CardController::class, 'index'])->name('card.list');
            Route::get('/payment/funding-instructions', [PaymentController::class, 'fundingInstructions'])->name('funding.instructions');
            Route::get('/payment/enabled-methods/{slug?}', [PaymentController::class, 'enabledMethods'])->name('payment.methods');
        });

    } else {
        Route::withoutMiddleware([
            VerifyCsrfToken::class,
        ])
        ->prefix(config('payment.route_prefix', '/api'))
        ->middleware(config('payment.middlewares', []))
        ->group(function () {
            Route::get('/payment/instances', [PaymentController::class, 'index'])->name('payment.get');
            Route::patch('/payment/instances/{paymentInstance}/ack', [PaymentController::class, 'ack'])->name('payment.ack');
            Route::post('/payment/request', [PaymentController::class, 'initPayment'])->name('payment.init');
            Route::post('/payment/paypal/order/{order}/capture', [PaypalController::class, 'captureOrder'])->name('payment.paypalOrderCapture');
            Route::any('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
            Route::any('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
            Route::delete('/payment/cards/{card}', [CardController::class, 'delete'])->name('card.delete');
            Route::post('/payment/cards', [CardController::class, 'create'])->name('card.create');
            Route::get('/payment/cards', [CardController::class, 'index'])->name('card.list');
            Route::get('/payment/funding-instructions', [PaymentController::class, 'fundingInstructions'])->name('funding.instructions');
            Route::get('/payment/enabled-methods/{slug?}', [PaymentController::class, 'enabledMethods'])->name('payment.methods');
        });
    }
} else {
    // This will be executed when Lumen Application is there

    if(config('payment.active_version') === 'v2') {
        $r = $this->app->router;
        $r->group([
            'prefix' => config('payment.route_prefix', '/api/v2'),
            'middleware' => config('payment.middlewares', []),
        ], function () use ($r) {
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
            $r->post(
                '/payment/paypal/order/{order}/capture', [
                    'as' => 'payment.paypalOrderCapture',
                    'uses' => 'Immera\Payment\Controllers\PaypalController@captureOrder',
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
            $r->delete(
                '/payment/cards/{card}', [
                    'as' => 'card.delete',
                    'uses' => 'Immera\Payment\Controllers\CardController@delete',
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
            $r->get(
                '/payment/funding-instructions', [
                    'as' => 'funding.instructions',
                    'uses' => 'Immera\Payment\Controllers\PaymentController@fundingInstructions',
                ]
            );
            $r->get(
                '/payment/enabled-methods[/{slug}]', [
                    'as' => 'payment.methods',
                    'uses' => 'Immera\Payment\Controllers\PaymentController@enabledMethods',
                ]
            );
        });
    }else{
        $r = $this->app->router;
        $r->group([
            'prefix' => config('payment.route_prefix', '/api'),
            'middleware' => config('payment.middlewares', []),
        ], function () use ($r) {
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
            $r->post(
                '/payment/paypal/order/{order}/capture', [
                    'as' => 'payment.paypalOrderCapture',
                    'uses' => 'Immera\Payment\Controllers\PaypalController@captureOrder',
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
            $r->delete(
                '/payment/cards/{card}', [
                    'as' => 'card.delete',
                    'uses' => 'Immera\Payment\Controllers\CardController@delete',
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
            $r->get(
                '/payment/funding-instructions', [
                    'as' => 'funding.instructions',
                    'uses' => 'Immera\Payment\Controllers\PaymentController@fundingInstructions',
                ]
            );
            $r->get(
                '/payment/enabled-methods[/{slug}]', [
                    'as' => 'payment.methods',
                    'uses' => 'Immera\Payment\Controllers\PaymentController@enabledMethods',
                ]
            );
        });
    }
}
