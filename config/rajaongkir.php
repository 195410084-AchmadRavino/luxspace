<?php

return [

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Key
    |--------------------------------------------------------------------------
    |
    | Set your RajaOngkir API key here. You can obtain the API key by
    | registering on the RajaOngkir website.
    |
    */
    'package' => 'Starter',
    'table_prefix' => 'rajaongkir_',// Ganti 'prefix_' dengan awalan yang Anda inginkan
    'timeout' => 10, // Ganti 10 dengan waktu timeout yang diinginkan dalam detik
    

    'api_key' => env('RAJAONGKIR_API_KEY', 'e927368d797bbea60a9dc6ab6724f348'),

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Endpoint
    |--------------------------------------------------------------------------
    |
    | Set the API endpoint based on your subscription (starter, basic, etc.).
    |
    */

    'api_endpoint' => env('RAJAONGKIR_API_ENDPOINT', 'https://api.rajaongkir.com'),

];
