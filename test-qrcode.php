<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

// Get the QrCodeService
$app = app();
$qrCodeService = $app->make(\App\Services\QrCodeService::class);

try {
    // Test generating a QR code
    $qrCode = $qrCodeService->generateQrCode('Test QR Code for Barangay Lumanglipa', 300);
    
    // Output headers for displaying an image
    header('Content-Type: text/html');
    
    // Output the result
    echo "<h1>QR Code Test</h1>";
    echo "<p>If you see a QR code image below, the Imagick extension is working correctly!</p>";
    echo "<img src=\"$qrCode\" alt=\"QR Code Test\">";
    echo "<p>Test completed successfully at: " . date('Y-m-d H:i:s') . "</p>";
    
} catch (Exception $e) {
    // Output any errors
    header('Content-Type: text/html');
    echo "<h1>QR Code Test Failed</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}