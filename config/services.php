<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'brevo' => [
        'api_key'    => env('BREVO_API_KEY', ''),
        'from_email' => env('BREVO_FROM_EMAIL', ''),
        'from_name'  => env('BREVO_FROM_NAME', 'HOLIC Barbershop'),
    ],

];