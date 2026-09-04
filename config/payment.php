<?php

return [

    'driver' => env('PAYMENT_DRIVER', 'development'),

    'providers' => [

        'development' => [
            'name' => 'Development Gateway',
            'sandbox' => true,
        ],

        'midtrans' => [
            'name' => 'Midtrans',
            'client_key' => env('MIDTRANS_CLIENT_KEY'),
            'server_key' => env('MIDTRANS_SERVER_KEY'),
            'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
            'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        ],

    ],

    'methods' => [
        'qris' => [
            'name' => 'QRIS',
            'description' => 'Scan QR Code',
            'icon' => 'qris',
        ],
        'bank_transfer' => [
            'name' => 'Transfer Bank',
            'description' => 'BCA, BNI, Mandiri',
            'icon' => 'bank',
        ],
        'ewallet' => [
            'name' => 'E-Wallet',
            'description' => 'GoPay, OVO, Dana',
            'icon' => 'ewallet',
        ],
    ],

    'expiry_hours' => 24,

    'webhook_path' => 'payment/webhook',

];
