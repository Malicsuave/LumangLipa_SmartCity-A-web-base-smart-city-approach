<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PDF Comparison Test ===" . PHP_EOL;

try {
    // Get first resident for testing
    $resident = \App\Models\Resident::first();
    if (!$resident) {
        echo 'No resident found for testing' . PHP_EOL;
        exit;
    }
    
    echo 'Testing with resident: ' . $resident->full_name . PHP_EOL;
    echo 'Barangay ID: ' . $resident->barangay_id . PHP_EOL;
    
    // Generate QR code
    $qrData = json_encode([
        'id' => $resident->barangay_id,
        'name' => $resident->full_name,
        'dob' => $resident->birthdate ? $resident->birthdate->format('Y-m-d') : null,
    ]);
    
    $qrCode = \App\Services\QrCode::generateQrCode($qrData, 300);
    if (strpos($qrCode, 'data:image/png;base64,') === 0) {
        $qrCode = substr($qrCode, 22);
    }
    
    echo 'QR code generated successfully' . PHP_EOL;
    
    // Check if images exist and their sizes
    $imageFiles = [
        'logo.png' => public_path('images/logo.png'),
        'citylogo.png' => public_path('images/citylogo.png'),
        'kahoylogo.png' => public_path('images/kahoylogo.png'),
        'bglumanglipa.jpeg' => public_path('images/bglumanglipa.jpeg'),
    ];
    
    foreach ($imageFiles as $name => $path) {
        if (file_exists($path)) {
            $size = filesize($path);
            echo "$name: EXISTS ($size bytes)" . PHP_EOL;
        } else {
            echo "$name: NOT FOUND ($path)" . PHP_EOL;
        }
    }
    
    // Test PDF generation with current settings
    echo PHP_EOL . "Generating PDF with current settings..." . PHP_EOL;
    
    $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.residents.id-pdf', [
        'resident' => $resident,
        'qrCode' => $qrCode,
    ]);
    
    // Use the same settings as ResidentIdController
    $pdf->setOptions([
        'page-width' => '148mm',
        'page-height' => '180mm',
        'orientation' => 'Portrait',
        'margin-top' => '0mm',
        'margin-right' => '0mm',
        'margin-bottom' => '0mm',
        'margin-left' => '0mm',
        'dpi' => 300,
        'image-quality' => 100,
        'disable-smart-shrinking' => true,
        'print-media-type' => true,
        'enable-local-file-access' => true,
        'load-error-handling' => 'ignore',
        'load-media-error-handling' => 'ignore'
    ]);
    
    $output = $pdf->output();
    $outputSize = strlen($output);
    
    echo "PDF generated successfully!" . PHP_EOL;
    echo "PDF size: $outputSize bytes" . PHP_EOL;
    
    // Save PDF to storage for comparison
    $filename = 'test_server_pdf_' . time() . '.pdf';
    $filepath = storage_path('app/' . $filename);
    file_put_contents($filepath, $output);
    
    echo "PDF saved to: $filepath" . PHP_EOL;
    echo "You can download this file to compare with your local version" . PHP_EOL;
    
    // Test with different settings to see what might be different
    echo PHP_EOL . "Testing with optimized settings..." . PHP_EOL;
    
    $pdf2 = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.residents.id-pdf', [
        'resident' => $resident,
        'qrCode' => $qrCode,
    ]);
    
    // Try with different font settings that might work better on server
    $pdf2->setOptions([
        'page-width' => '148mm',
        'page-height' => '180mm',
        'orientation' => 'Portrait',
        'margin-top' => '0mm',
        'margin-right' => '0mm',
        'margin-bottom' => '0mm',
        'margin-left' => '0mm',
        'dpi' => 300,
        'image-quality' => 100,
        'disable-smart-shrinking' => true,
        'print-media-type' => true,
        'enable-local-file-access' => true,
        'load-error-handling' => 'ignore',
        'load-media-error-handling' => 'ignore',
        'encoding' => 'UTF-8',
        'no-stop-slow-scripts' => true,
    ]);
    
    $output2 = $pdf2->output();
    $outputSize2 = strlen($output2);
    
    echo "Optimized PDF generated successfully!" . PHP_EOL;
    echo "Optimized PDF size: $outputSize2 bytes" . PHP_EOL;
    
    // Save optimized PDF
    $filename2 = 'test_server_pdf_optimized_' . time() . '.pdf';
    $filepath2 = storage_path('app/' . $filename2);
    file_put_contents($filepath2, $output2);
    
    echo "Optimized PDF saved to: $filepath2" . PHP_EOL;
    
    echo PHP_EOL . "=== Test Complete ===" . PHP_EOL;
    echo "Compare these server-generated PDFs with your local version to see the differences." . PHP_EOL;
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo 'File: ' . $e->getFile() . PHP_EOL;
    echo 'Line: ' . $e->getLine() . PHP_EOL;
}
