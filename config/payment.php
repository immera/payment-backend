<?php

return [
    'stripe' => [
        'secret_key' => env('STRIPE_SECRET'),
    ],
    'route_prefix' => env('PAYMENT_ROUTE_PREFIX', "/api"),
];
