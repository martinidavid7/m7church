<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Sanctum Authentication Guard
    |--------------------------------------------------------------------------
    |
    | This value determines the authentication guard that Sanctum will use to
    | authenticate incoming requests. You may change this to any guard that
    | is defined in your application, such as "web" or "api".
    |
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Sanctum Statefull Domains
    |--------------------------------------------------------------------------
    |
    | Here you may specify the domains that should be considered stateful for
    | Sanctum authentication. These domains will receive a CSRF cookie when
    | using the "EnsureFrontendRequestsAreStateful" middleware.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),


];