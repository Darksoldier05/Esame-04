<?php

return [

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'contatti',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'contatti',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'contatti',
            'hash' => false,
        ],
    ],

    'providers' => [
        'contatti' => [
            'driver' => 'eloquent',
            'model' => App\Models\Contatto::class,
        ],
    ],

    'passwords' => [
        'contatti' => [
            'provider' => 'contatti',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
