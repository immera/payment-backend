<?php

use Adiechahk\PaymentBackend\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware([
    \App\Http\Middleware\VerifyCsrfToken::class
])->group(function() {
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
