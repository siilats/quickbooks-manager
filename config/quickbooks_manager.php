<?php

return [

    'default_connection' => env('QBM_DEFAULT_CONNECTION', 'default'),

    'logs_path' => storage_path('logs'),

    'session_redirect_key' => 'qbm_redirect_back',

    'callback_route' => 'qbm.callback',

    'default_redirect_route' => 'app.home',

    'connections' => [

        'default' => [
            'client_id' => env('QBM_DEFAULT_CLIENT_ID', null),
            'client_secret' => env('QBM_DEFAULT_CLIENT_SECRET', null),
            'scope' => env('QBM_DEFAULT_SCOPE', 'com.intuit.quickbooks.accounting'),
            'base_url' => env('QBM_DEFAULT_BASE_URL', 'Development'), // Development or Production
        ],

    ],

];