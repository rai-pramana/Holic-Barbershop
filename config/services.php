<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'fonnte' => [
        'token'   => env('FONNTE_TOKEN', ''),
        'enabled' => env('FONNTE_ENABLED', true),
    ],

    'brevo' => [
        'api_key'    => env('BREVO_API_KEY', ''),
        'from_email' => env('BREVO_FROM_EMAIL', ''),
        'from_name'  => env('BREVO_FROM_NAME', 'HOLIC Barbershop'),
    ],

];