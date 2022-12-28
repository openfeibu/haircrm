<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', 'AeS3qUDiB_h1VTXSpNRh0ZSLYlCXHFW78afckyiwgwdAb86o1YF6H4Drrxp95vRdJr8pFNkJQ-hIIm-M'),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', 'EPUxKoFTITmwjz9q8T6zW-u15QeBsFFUlmUAv2bEPTYNj3Abu0MwSsvBVTciqLqZyaIKvDBgutn7xzQ6'),
        'app_id'            => 'APP-80W284485P519543T',
    ],
    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', 'AXfAKkapgBKmUZ7nWhr8iCeF1oEMQHzx_2Yrx3oTRHI69gNLu_adz2oFIZzhG-VX5gE44T8zhMfEKqRY'),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', 'EE9Rez25HH4keOWBv-PHzdKvM04IbpjIp6R7Forq3mxEwD7zEsbZkCM6dlsK4uSDp8pqkAtxFle2utwg'),
        'app_id'            => env('PAYPAL_LIVE_APP_ID', 'APP-30E83770MU6588636'),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
