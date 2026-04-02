<?php

return [
    'groups' => [
        'general' => 'General',
        'display' => 'Display',
        'rating' => 'Rating',
        'performance' => 'Performance',
        'report' => 'Report',
        'visitor' => 'Visitor',
        'unit' => 'Unit',
        'audit' => 'Audit',
        'notification' => 'Notification',
        'security' => 'Security',
        'api' => 'API',
    ],

    'defaults' => [
        'app_name' => env('APP_NAME', 'Unit Rating Feedback'),
        'app_timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
        'app_locale' => env('APP_LOCALE', 'id'),
        'app_url' => env('APP_URL', 'http://localhost:8000'),
        'rating_scale_min' => 1,
        'rating_scale_max' => 5,
        'rating_allow_decimal' => false,
        'unit_rating_enabled' => true,
        'max_units_per_user' => 10,
        'collect_visitor_data' => true,
        'require_two_factor' => false,
        'cache_enabled' => true,
        'api_enabled' => true,
    ],

    'cache' => [
        'enabled' => env('SETTINGS_CACHE_ENABLED', true),
        'ttl' => env('SETTINGS_CACHE_TTL', 604800),
    ],
];