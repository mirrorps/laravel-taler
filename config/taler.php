<?php

return [
    'base_url' => env('TALER_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | If a token is provided it takes precedence over the username/password
    | flow. Otherwise the SDK will use the provided merchant credentials and
    | instance identifier to obtain and refresh a token automatically.
    |
    */
    'token' => env('TALER_TOKEN'),
    'username' => env('TALER_USERNAME'),
    'password' => env('TALER_PASSWORD'),
    'instance_id' => env('TALER_INSTANCE_ID'),
    'scope' => env('TALER_SCOPE', 'readonly'),
    'duration_us' => env('TALER_DURATION_US'),
    'description' => env('TALER_DESCRIPTION'),

    'wrap_response' => env('TALER_WRAP_RESPONSE', true),
    'debug_logging_enabled' => env('TALER_DEBUG_LOGGING_ENABLED', false),
];
