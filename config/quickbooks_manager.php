<?php

return [

    'default_connection' => env('QBM_DEFAULT_CONNECTION', 'default'),

    'logs_path' => storage_path('logs'),

    'webhook_logs_enabled' => env('QBM_WEBHOOK_LOGS_ENABLED', true),

    'webhook_logs_channel' => env('QBM_WEBHOOK_LOGS_CHANNEL', 'stack'),

    'callback_route' => 'qbm.callback',

    'redirect_route' => 'app.home',

    'connections' => [

        'default' => [
            'client_id' => env('QBM_DEFAULT_CLIENT_ID', null),
            'client_secret' => env('QBM_DEFAULT_CLIENT_SECRET', null),
            'scope' => env('QBM_DEFAULT_SCOPE', 'com.intuit.quickbooks.accounting'),
            'base_url' => env('QBM_DEFAULT_BASE_URL', 'Development'), // Development or Production
            'verifier_token' => env('QBM_DEFAULT_VERIFIER_TOKEN', null),
        ],

    ],

];