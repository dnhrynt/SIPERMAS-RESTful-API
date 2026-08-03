<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Tentukan domain frontend yang diizinkan mengakses API ini
    'allowed_origins' => [
        'http://localhost:3000', // Contoh: React / Next.js dev server
        'http://localhost:5173', // Contoh: Vite / Vue / React dev server
        // 'https://sipermas.domainkamu.com', // Domain frontend saat production
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Set 'true' jika kamu menggunakan Cookie/Sanctum SPA authentication

];