<?php

return [

    // IMPOSTAZIONI PREDEFINITE: usa il guard API per default
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    // QUI definisci i GUARDS (sistemi di autenticazione)
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
    ],


    // Provider: come recuperare gli utenti dal database
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    // Gestione reset password (puoi lasciarlo standard)
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    // Timeout per la conferma password (default 3 ore)
    'password_timeout' => 10800,

];
