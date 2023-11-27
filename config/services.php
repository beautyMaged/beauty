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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_SERVICE_CALLBACK'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_SERVICE_CALLBACK'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_SERVICE_CALLBACK'),
    ],

    'shopify' => [
        'client_id'          => env('SHOPIFY_OAUTH_CLIENT_ID'),
        'client_secret'      => env('SHOPIFY_OAUTH_CLIENT_SECRET'),
        'scopes'             => ['read_products'],
    ],

    'salla' => [
        'client_id'          => env('SALLA_OAUTH_CLIENT_ID'),
        'client_secret'      => env('SALLA_OAUTH_CLIENT_SECRET'),
        'redirect'           => env('SALLA_OAUTH_CLIENT_REDIRECT_URI'),
        'webhook_secret'     => env('SALLA_WEBHOOK_SECRET'),
        'authorization_mode' => env('SALLA_AUTHORIZATION_MODE', 'easy'),   // Supported: "easy", "custom"
        'scopes'             => ['offline_access'],

    ],

    'zid' => [
        'client_id'          => env('ZID_OAUTH_CLIENT_ID'),
        'client_secret'      => env('ZID_OAUTH_CLIENT_SECRET'),
        // 'webhook_secret'     => env('ZID_WEBHOOK_SECRET'),
    ]

];
