<?php
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "bootstrap ok\n";
} catch (Throwable $e) {
    echo 'Exception: ' . $e->getMessage() . "\n";
    @file_put_contents('/tmp/laravel_bootstrap_error.log', $e->getMessage() . "\n" . $e->getTraceAsString(), FILE_APPEND);
}
