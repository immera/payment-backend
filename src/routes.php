<?php

use Adiechahk\PaymentBackend\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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
