<?php

return [
    'manifest' => storage_path('framework/vite-manifest.json'),

    'hot_file' => public_path('hot'),

    'build_path' => 'build',

    'package_manager' => env('VITE_PACKAGE_MANAGER', 'npm'),

    'react_refresh_paths' => [
        'resources/js/app.jsx',
    ],
];
