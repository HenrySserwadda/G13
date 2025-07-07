<?php

return [
    'default' => env('BROADCAST_DRIVER', 'reverb'),

    'connections' => [
        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY', 'local_key'),
            'secret' => env('REVERB_APP_SECRET', 'local_secret'),
            'app_id' => env('REVERB_APP_ID', 'local_app'),
            'options' => [
                'host' => env('REVERB_HOST', '127.0.0.1'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
            ],
        ],

        // Other connections can remain if needed
        'log' => [
            'driver' => 'log',
        ],
        
        'null' => [
            'driver' => 'null',
        ],
    ],
];