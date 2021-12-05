<?php


return [
    'cache_remember' => env('API_CACHE_REMEMBER'),

    'zip_api' => [
        'url' => env('API_POSTCODES_URL'),
    ],

    'distance_api' => [
        'url' => env('API_GRAPHHOPPER_URL'),
        'key' => env('API_GRAPHHOPPER_KEY'),
    ],

    'zip' => env('REAL_ESTATE_ZIP_CODE'),
    'lat' => env('REAL_ESTATE_LAT'),
    'lng' => env('REAL_ESTATE_LNG'),

    'appointment_time' => env('APPOINTMENT_TIME'),
];
