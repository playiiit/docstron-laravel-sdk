<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Docstron API Key
    |--------------------------------------------------------------------------
    |
    | Your Docstron API key. You can find this in your Docstron dashboard.
    |
    */
    'api_key' => env('DOCSTRON_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Docstron Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Docstron API.
    |
    */
    'base_url' => env('DOCSTRON_BASE_URL', 'https://api.docstron.com/v1'),
];
