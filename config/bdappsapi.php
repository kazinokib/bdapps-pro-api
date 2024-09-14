<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BDApps API Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials and configuration for the
    | BDApps API integration. You can set these values in your .env file.
    |
    */

    // The application ID provided by BDApps
    'app_id' => env('BDAPPS_APP_ID'),

    // The application password provided by BDApps
    'app_password' => env('BDAPPS_APP_PASSWORD'),

    // The base URL for the BDApps API
    'base_url' => env('BDAPPS_BASE_URL', 'https://developer.bdapps.com'),

    // Default SMS settings
    'sms' => [
        'source_address' => env('BDAPPS_SMS_SOURCE_ADDRESS', ''),
        'delivery_status_request' => env('BDAPPS_SMS_DELIVERY_STATUS_REQUEST', 0),
        'encoding' => env('BDAPPS_SMS_ENCODING', '0'), // 0 for Text, 16 for Bengali, etc.
    ],

    // Default USSD settings
    'ussd' => [
        'encoding' => env('BDAPPS_USSD_ENCODING', '440'), // 440 for Plain ASCII, 16 for Bengali
    ],

    // Default CAAS settings
    'caas' => [
        'payment_instrument_name' => env('BDAPPS_CAAS_PAYMENT_INSTRUMENT', 'Mobile Account'),
    ],

    // Timeout for API requests in seconds
    'timeout' => env('BDAPPS_API_TIMEOUT', 10),

    // Whether to verify SSL certificate
    'verify_ssl' => env('BDAPPS_VERIFY_SSL', true),

];
