<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Form Validation Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for form validation security settings
    | and validation rules that are used throughout the application.
    |
    */

    'security' => [
        /*
        |--------------------------------------------------------------------------
        | Rate Limiting
        |--------------------------------------------------------------------------
        |
        | Configure rate limiting for form submissions to prevent abuse.
        |
        */
        'rate_limiting' => [
            'max_attempts' => env('FORM_RATE_LIMIT_ATTEMPTS', 10),
            'decay_minutes' => env('FORM_RATE_LIMIT_DECAY', 1),
        ],

        /*
        |--------------------------------------------------------------------------
        | File Upload Validation
        |--------------------------------------------------------------------------
        |
        | Configure file upload validation settings.
        |
        */
        'file_uploads' => [
            'allowed_image_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
            'max_file_size' => 1024 * 1024, // 1MB in bytes
        ],

        /*
        |--------------------------------------------------------------------------
        | Input Sanitization
        |--------------------------------------------------------------------------
        |
        | Configure input sanitization settings.
        |
        */
        'sanitization' => [
            'remove_null_bytes' => true,
            'remove_control_chars' => true,
            'normalize_unicode' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Suspicious Pattern Detection
        |--------------------------------------------------------------------------
        |
        | Enable/disable detection of suspicious patterns in user input.
        |
        */
        'pattern_detection' => [
            'enabled' => env('SECURITY_PATTERN_DETECTION', true),
            'log_attempts' => env('SECURITY_LOG_ATTEMPTS', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Common validation rules used across the application.
    |
    */
    'rules' => [
        'name' => [
            'min_length' => 2,
            'max_length' => 255,
            'pattern' => '/^[a-zA-Z\s\.\-\']+$/',
        ],
        
        'email' => [
            'max_length' => 255,
            'validation_type' => 'rfc,dns',
        ],
        
        'password' => [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => true,
        ],
        
        'reason_text' => [
            'min_length' => 10,
            'max_length' => 1000,
            'pattern' => '/^[a-zA-Z0-9\s\.\,\!\?\-\(\)\'\"]+$/',
        ],
        
        'notes' => [
            'min_length' => 3,
            'max_length' => 1000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Error Messages
    |--------------------------------------------------------------------------
    |
    | Custom error messages for validation rules.
    |
    */
    'messages' => [
        'malicious_content' => 'The :attribute contains potentially harmful content.',
        'invalid_characters' => 'The :attribute contains invalid characters.',
        'rate_limit_exceeded' => 'Too many attempts. Please wait before trying again.',
        'file_size_exceeded' => 'File size must not exceed :max MB.',
        'invalid_file_type' => 'Please select a valid file type (:types).',
    ],
];