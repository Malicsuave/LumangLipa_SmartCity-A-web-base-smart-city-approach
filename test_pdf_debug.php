<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PDF Debug Test ===" . PHP_EOL;

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
    
    // Check if template files exist
    $templatePath = resource_path('views/admin/residents/id-pdf.blade.php');
    echo 'Template exists: ' . (file_exists($templatePath) ? 'YES' : 'NO') . PHP_EOL;
    
    // Check if logo files exist
    $logoFiles = [
        'logo.png' => public_path('images/logo.png'),
        'citylogo.png' => public_path('images/citylogo.png'),
        'kahoylogo.png' => public_path('images/kahoylogo.png')
    ];
    
    foreach ($logoFiles as $name => $path) {
        echo $name . ' exists: ' . (file_exists($path) ? 'YES' : 'NO') . ' (' . $path . ')' . PHP_EOL;
    }
    
    // Generate PDF with actual template and settings from ResidentIdController
    echo 'Generating PDF...' . PHP_EOL;
    
    $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.residents.id-pdf', [
        'resident' => $resident,
        'qrCode' => $qrCode,
    ]);
    
    // Use exact same settings as ResidentIdController
    $pdf->setOptions([
        'page-width' => '148mm',
        'page-height' => '180mm',
        'orientation' => 'Portrait',
        'margin-top' => '5mm',
        'margin-right' => '5mm',
        'margin-bottom' => '5mm',
        'margin-left' => '5mm',
        'encoding' => 'UTF-8',
        'enable-local-file-access' => true,
        'disable-smart-shrinking' => true,
        'print-media-type' => true,
        'no-outline' => true,
        'disable-external-links' => true,
        'disable-internal-links' => true,
        'disable-javascript' => true,
        'no-images' => false,
        'dpi' => 300,
        'image-quality' => 100,
        'zoom' => 1.0,
        'viewport-size' => '1280x1024',
        'javascript-delay' => 0,
        'load-error-handling' => 'ignore',
        'load-media-error-handling' => 'ignore'
    ]);
    
    $output = $pdf->output();
    echo 'PDF generated successfully!' . PHP_EOL;
    echo 'PDF size: ' . strlen($output) . ' bytes' . PHP_EOL;
    
    // Save to file for inspection
    $filename = '/tmp/test_resident_id.pdf';
    file_put_contents($filename, $output);
    echo 'PDF saved to: ' . $filename . PHP_EOL;
    
    // Show first few bytes to verify it's a valid PDF
    echo 'PDF header: ' . substr($output, 0, 20) . PHP_EOL;
    
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo 'File: ' . $e->getFile() . PHP_EOL;
    echo 'Line: ' . $e->getLine() . PHP_EOL;
    echo 'Trace: ' . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}

echo "=== Test Complete ===" . PHP_EOL;
