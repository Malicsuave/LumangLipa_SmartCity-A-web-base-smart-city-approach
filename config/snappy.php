<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
    
    'pdf' => [
        'enabled' => true,
        'binary'  => '/usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true,
            'encoding' => 'UTF-8',
            'page-size' => 'A4',
            'orientation' => 'Portrait',
            'margin-top' => '0mm',
            'margin-right' => '0mm',
            'margin-bottom' => '0mm',
            'margin-left' => '0mm',
            'dpi' => 300,
            'image-quality' => 100,
            'disable-smart-shrinking' => true,
            'print-media-type' => true,
            'no-background' => false,
            'lowquality' => false,
            'disable-javascript' => false,
            'no-stop-slow-scripts' => true,
            'disable-external-links' => true,
            'disable-internal-links' => true,
            'quiet' => true,
        ],
        'env'     => [],
    ],
    
    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', '/usr/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
