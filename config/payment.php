<?php

return [
    'stripe' => [
        'secret_key' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'country' => env('STRIPE_COUNTRY', 'FR'),
    ],
    'paypal' => [
        'url' => env('PAYPAL_URL', "https://api-m.sandbox.paypal.com/"),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret_key' => env('PAYPAL_CLIENT_SECRET'),
    ],
    'payment_methods' => [
        'default' => ['alipay', 'wechat_pay', 'multibanco', 'bank_transfer', 'card', 'paypal', 'cash']
    ],
    'route_prefix' => env('PAYMENT_ROUTE_PREFIX', "/api"),
    'active_version' => 'v1', // you can use v0 to access older code.
    'stripe_version_date' => env('STRIPE_VERSION_DATE', "2023-10-16"), // set stripe version date.
    'middlewares' => [],
];
