<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    |
    | Configure sitemap generation settings
    |
    */

    'use_cache' => true,
    'cache_key' => 'laravel-sitemap.sitemap',
    'cache_duration' => 3600, // Cache for 1 hour

    'url' => env('APP_URL', 'http://localhost'),

    'route_prefix' => 'sitemap',
];
