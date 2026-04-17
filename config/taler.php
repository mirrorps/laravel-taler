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

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | The Taler SDK accepts any PSR-3 logger. By default the package forwards
    | log records to your application's default log channel (the one defined
    | under `config/logging.php`'s `default` key).
    |
    | `logging_enabled` is an explicit on/off switch. When set to false the
    | SDK is given a PSR-3 NullLogger and Laravel's logging stack is
    | bypassed entirely for Taler records.
    |
    | `log_channel` selects which Laravel log channel receives SDK log
    | records when logging is enabled. Leave it null/empty to use the
    | application's default channel, or set it to any channel name declared
    | in `config/logging.php` (for example a dedicated `taler` channel).
    |
    | `debug_logging_enabled` toggles the SDK's own DEBUG-level request and
    | response logging. It is independent from `logging_enabled`; error-level
    | logging from the SDK is always emitted as long as `logging_enabled`
    | is true.
    |
    */
    'logging_enabled' => env('TALER_LOGGING_ENABLED', true),
    'log_channel' => env('TALER_LOG_CHANNEL'),
    'debug_logging_enabled' => env('TALER_DEBUG_LOGGING_ENABLED', false),
];
