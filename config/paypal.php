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
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', 'AU2NrqePdJd4lZWt4xeDbSg-z_9qrk_FkZ-mDZinUhbu4tGTxuXBQ8_1rVI1ggNtc9_k4rWqSKJ4UVqY'),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', 'EIoreSDRaDyhS8CpAiPYZ1xbWUSfYO3x_Zy2O9R-NnY8QZaEk6tTObfEs45oRK7pvdB7rHmiX81ra2YS'),
        'app_id'            => env('PAYPAL_LIVE_APP_ID', 'APP-1WJ92261DX832905Y'),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
