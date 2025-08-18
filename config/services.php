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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    'phpmailer' => [
        'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
        'smtp_port' => env('MAIL_PORT', 587),
        'smtp_username' => env('MAIL_USERNAME'),
        'smtp_password' => env('MAIL_PASSWORD'),
        'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
    ],

    'huggingface' => [
    'api_key' => env('HUGGINGFACE_API_KEY'),
    // When true, the chatbot will NOT fall back to canned replies if AI fails.
    // Auto-enable if no API key is provided.
    'strict' => (bool) (env('CHATBOT_STRICT_AI', false) || !env('HUGGINGFACE_API_KEY')),
    ],
];
