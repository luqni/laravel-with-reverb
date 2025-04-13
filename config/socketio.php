<?php

return [
    'host' => env('SOCKET_HOST', 'localhost'),
    'port' => env('SOCKET_PORT', 6001),
    'options' => [
        'cors' => [
            'origin' => ['*'],
            'methods' => ['GET', 'POST'],
            'allowedHeaders' => ['*'],
            'exposedHeaders' => [],
            'maxAge' => 0,
            'credentials' => false
        ],
    ],
];