<?php

return [
    // URL for the OPCache API
    'url' => env('OPCACHE_URL', config('app.url')),

    // API prefix for the OPCache routes
    'prefix' => 'opcache-api',

    // SSL verification settings
    'verify_ssl' => false,    // Disable SSL verification
    'verify_host' => 0,       // Disable host verification

    // Additional headers that may be needed for requests
    'headers' => [],

    // Directories to include in the OPCache
    'directories' => [
        base_path('app'),
        base_path('bootstrap'),
        base_path('public'),
        base_path('resources'),
        base_path('routes'),
        base_path('storage'),
        base_path('vendor'),
    ],

    // Directories and files to exclude from OPCache
    'exclude' => [
        'test',
        'Test',
        'tests',
        'Tests',
        'stub',
        'Stub',
        'stubs',
        'Stubs',
        'dumper',
        'Dumper',
        'Autoload',
    ],
];
