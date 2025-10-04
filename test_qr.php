<?php

// Simple test script to generate QR codes for testing
require_once 'vendor/autoload.php';

use chillerlan\QRCode\QRCode;

// Test data structure that should be in QR codes for residents
$residentData = [
    'barangay_id' => 1,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com'
];

// Generate QR code with JSON data
$qrData = json_encode($residentData);

// Create QR code generator
$qrCode = new QRCode();

// Generate QR code image
$qrCodeImage = $qrCode->render($qrData);

// Save as file for testing
file_put_contents('test_qr_code.png', $qrCodeImage);

echo "Test QR code generated with data:\n";
echo $qrData . "\n";
echo "Saved as test_qr_code.png\n";

// Also test with simple string format
$simpleData = "barangay_id:1,name:John Doe,email:john.doe@example.com";
$simpleQrCodeImage = $qrCode->render($simpleData);
file_put_contents('test_qr_code_simple.png', $simpleQrCodeImage);

echo "\nSimple format QR code generated with data:\n";
echo $simpleData . "\n";
echo "Saved as test_qr_code_simple.png\n";

?>