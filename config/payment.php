<?php

return [
    'stripe' => [
        'secret_key' => env('STRIPE_SECRET'),
    ],
    'paypal' => [
        'url' => env('PAYPAL_URL', "https://api-m.sandbox.paypal.com/"),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret_key' => env('PAYPAL_CLIENT_SECRET'),
    ],
    'route_prefix' => env('PAYMENT_ROUTE_PREFIX', "/api"),
];
