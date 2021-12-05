<?php


return [

    'cache_remember' => env('API_CACHE_REMEMBER'),

    'api' => [
        'distance' => env('API_DISTANCE_URL'),
        'postcodes' => env('API_POSTCODES_URL'),
    ],


    'zip' => env('REAL_ESTATE_ZIP_CODE'),
    'lat' => env('REAL_ESTATE_LAT'),
    'lng' => env('REAL_ESTATE_LNG'),

    'appointment_time' => env('APPOINTMENT_TIME'),
];
