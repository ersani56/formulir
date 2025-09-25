<?php

return [
    'path' => 'admin', // Pastikan path benar
    'domain' => null, // Biarkan null untuk subdomain yang sama
    'home_url' => '/',
    'brand' => env('APP_NAME', 'Laravel'),

    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],
    ],

    // ... lainnya
];
