<?php
// Test if Laravel can be loaded
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Laravel loaded successfully!";
} catch (Exception $e) {
    echo "Error loading Laravel: " . $e->getMessage();
}
?> 