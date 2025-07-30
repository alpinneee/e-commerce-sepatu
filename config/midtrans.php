<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    'is_sanitized' => true,
    'is_3ds' => true,
    
    'notification_url' => env('APP_URL') . '/midtrans/notification',
    'finish_url' => env('APP_URL') . '/checkout/success',
    'unfinish_url' => env('APP_URL') . '/checkout',
    'error_url' => env('APP_URL') . '/checkout',
    
    'enable_payments' => [
        'credit_card',
        'cimb_clicks',
        'bca_klikbca',
        'bca_klikpay',
        'bri_epay',
        'echannel',
        'permata_va',
        'bca_va',
        'bni_va',
        'other_va',
        'gopay',
        'indomaret',
        'danamon_online',
        'akulaku',
        'shopeepay',
        'kredivo',
        'uob_ezpay'
    ]
]; 