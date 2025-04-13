<?php

return [
    'host' => env('REVERB_SERVER_HOST', '127.0.0.1'),
    'port' => env('REVERB_SERVER_PORT', 8080),
    'scheme' => env('REVERB_SERVER_SCHEME', 'http'),
    'domain' => env('APP_URL', 'http://localhost'),
    'path' => env('REVERB_SERVER_PATH', 'reverb'),
    'hostname' => env('REVERB_SERVER_HOSTNAME', null),
    'max_request_size' => env('REVERB_MAX_REQUEST_SIZE_IN_MB', 10) * 1024 * 1024,
    'verify_peer' => env('REVERB_VERIFY_PEER', true),
    'max_connections' => env('REVERB_MAX_CONNECTIONS', 1000),
    'apps' => [
        'main' => [
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'app_id' => env('REVERB_APP_ID'),
            'options' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
            ],
            'ssl_verifyhost' => env('REVERB_SSL_VERIFYHOST', true),
            'ssl_verifypeer' => env('REVERB_SSL_VERIFYPEER', true),
        ],
    ],
];