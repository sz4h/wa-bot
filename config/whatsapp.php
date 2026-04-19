<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the default WhatsApp driver and its specific credentials.
    |
    | Supported drivers: 'evolutionapi' (currently)
    |
    */

    'default_driver' => env('WHATSAPP_DEFAULT_DRIVER', 'evolutionapi'),

    'evolutionapi' => [
        'instance_id' => env('EVOLUTIONAPI_INSTANCE_ID'),
        'token' => env('EVOLUTIONAPI_TOKEN'),
        'base_url' => env('EVOLUTIONAPI_BASE_URL', 'https://api.evolutionapi.com'), // Default URL
    ],

    // Example for another driver (e.g., Ultramsg)
    // 'ultramsg' => [
    //     'api_token' => env('ULTRAMSG_API_TOKEN'),
    //     'instance_id' => env('ULTRAMSG_INSTANCE_ID'),
    //     'instance_token' => env('ULTRAMSG_INSTANCE_TOKEN'),
    // ],

];
