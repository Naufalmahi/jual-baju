<?php

return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'environment' => env('MIDTRANS_ENVIRONMENT', 'sandbox'), // sandbox or production
    'is_production' => env('MIDTRANS_ENVIRONMENT') === 'production',
    'snap_url' => env('MIDTRANS_ENVIRONMENT', 'sandbox') === 'production'
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',
    'options' => [
        'headers' => [
            'User-Agent' => 'Laravel Midtrans Integration',
        ]
    ]
];
