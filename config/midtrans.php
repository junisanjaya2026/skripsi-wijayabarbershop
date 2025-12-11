<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    |
    | This value is the server key provided by Midtrans for authenticating API
    | requests. Make sure to keep this key secure and do not expose it publicly.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    |
    | This value is the client key provided by Midtrans for client-side
    | integrations. It is safe to expose this key in your frontend code.
    |
    */

    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Environment
    |--------------------------------------------------------------------------
    |
    | This setting determines whether to use the production or sandbox
    | environment for Midtrans transactions. Set to true for production.
    |
    */

    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Merchant ID
    |--------------------------------------------------------------------------
    |
    | This value is the merchant ID provided by Midtrans for identifying your
    | account in their system.
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),

];
