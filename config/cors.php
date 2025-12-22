<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // ✅ FIX: Tambahkan 'storage/*' untuk akses gambar
    'paths' => [
        'api/*',
        'storage/*',  // ← PENTING: Biar frontend bisa akses gambar
        'sanctum/csrf-cookie'
    ],

    'allowed_methods' => ['*'],

    // ✅ FIX: Support localhost di berbagai port
    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:5174',  // ← Jika pakai port alternatif
        'http://localhost:3000',  // ← Jika pakai port lain
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
