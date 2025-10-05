<?php

require_once 'vendor/autoload.php';

use Zxing\QrReader;

// Test the QR decoder functionality
$testImagePath = 'public/favicon.ico'; // Use any existing image for testing

if (file_exists($testImagePath)) {
    echo "Testing QR decoder with file: $testImagePath\n";
    
    try {
        $qrcode = new QrReader($testImagePath);
        $text = $qrcode->text();
        
        if ($text !== false) {
            echo "QR Code detected: " . $text . "\n";
        } else {
            echo "No QR code found in the image.\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Test image file not found: $testImagePath\n";
    
    // List available files for testing
    echo "Available files in public directory:\n";
    if (is_dir('public')) {
        $files = scandir('public');
        foreach ($files as $file) {
            if (is_file('public/' . $file)) {
                echo "- public/$file\n";
            }
        }
    }
}

?>