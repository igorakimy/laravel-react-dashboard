<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'zoho_books' => [
        'domain' => env('ZOHO_BOOKS_DOMAIN', 'com'),
        'client_id' => env('ZOHO_BOOKS_CLIENT_ID', 'client_id'),
        'client_secret' => env('ZOHO_BOOKS_CLIENT_SECRET', 'client_secret'),
        'redirect_uri' => env('APP_URL') . '/check/zoho-books',
        'organization_id' => env('ZOHO_BOOKS_ORGANIZATION_ID', 'organization_id'),
        'scopes' => [
            'ZohoBooks.fullaccess.all',
        ],
        'use_cache' => true,
    ],

    'zoho_inventory' => [
        'domain' => env('ZOHO_INVENTORY_DOMAIN', 'com'),
        'client_id' => env('ZOHO_INVENTORY_CLIENT_ID', 'client_id'),
        'client_secret' => env('ZOHO_INVENTORY_CLIENT_SECRET', 'client_secret'),
        'redirect_uri' => env('APP_URL') . '/check/zoho-inventory',
        'organization_id' => env('ZOHO_INVENTORY_ORGANIZATION_ID', 'organization_id'),
        'scopes' => [
            'ZohoInventory.fullaccess.all',
        ],
        'use_cache' => true,
    ],

    'zoho_crm' => [
        'domain' => env('ZOHO_CRM_DOMAIN', 'com'),
        'client_id' => env('ZOHO_CRM_CLIENT_ID', 'client_id'),
        'client_secret' => env('ZOHO_CRM_CLIENT_SECRET', 'client_secret'),
        'redirect_uri' => env('APP_URL') . '/check/zoho-crm',
        'scopes' => [
            'ZohoCRM.modules.ALL',
            'ZohoCRM.settings.ALL',
            'ZohoCRM.Files.CREATE',
        ],
        'use_cache' => true,
    ],
];
