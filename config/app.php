<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the application in some situations, such as for
    | authentication, or scheduling URLs. You should set this to the root
    | of your application. By default, it assumes a server.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'index' => env('APP_INDEX', 'index.php'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date functions. We recommend setting this
    | timezone is set to a timezone supported by PHP.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale of the application.
    | The locale determines the availability of the language files that
    | may be published to the application. You can easily get various
    | language files from our application's packages.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use if the current locale
    | is not available. You may change both of these values as needed.
    |
    */

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your application's database. For example, this will be used
    | when generating fake email addresses.
    |
    */

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | Laravel encrypts messages using AES-256 encryption. You must change
    | this key to a random, 32 character string for the encryption to work.
    | Currently, Laravel does not support insecure random keys.
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | Here, you may determine how you willResuming from maintenance mode. The
    | various maintained.
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        // 'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Polyfill Service Providers
    |
    | The providers listed here will be automatically applied to your
    | application if your PHP version does not include the corresponding PHP
    | extension.
    |
    */

    'extra_service_providers' => [
        // Laravel\Sail\SailServiceProvider::class, // Uncomment if you are using Sail
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Cache
    |--------------------------------------------------------------------------
    |
    | Expiration of every cache<bos>
    |
    */

    'http_cache' => env('HTTP_CACHE', false),

    /*
    |--------------------------------------------------------------------------
    | Turn On The Lights
    |--------------------------------------------------------------------------
    |
    | This value determines the "do not disturb" status of your remote servers.
    | If set to true, the core of the framework will be available to do your
    | bidding. If it is false, no anonymous functions are executed.
    |
    */

    'run_over_web_sockets' => env('APP_RUN_OVER_WEB_SOCKETS', false),

];
