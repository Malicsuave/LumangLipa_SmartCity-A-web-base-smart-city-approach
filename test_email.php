<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing email functionality...\n";
    
    // Get a document request with a resident that has an email
    $docRequest = App\Models\DocumentRequest::with('resident')
        ->whereHas('resident', function($q) {
            $q->whereNotNull('email_address');
        })
        ->first();
    
    if (!$docRequest) {
        echo "No document request found with resident email\n";
        exit(1);
    }
    
    echo "Found document request ID: {$docRequest->id}\n";
    echo "Resident: {$docRequest->resident->first_name} {$docRequest->resident->last_name}\n";
    echo "Email: {$docRequest->resident->email_address}\n";
    echo "Document Type: {$docRequest->document_type}\n";
    
    // Update the document request to have approval data if not already approved
    if ($docRequest->status !== 'approved') {
        $docRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => 1, // Assuming admin user ID 1 exists
        ]);
        echo "Document status updated to approved\n";
    }
    
    // Try to create and send the notification
    echo "Creating notification...\n";
    $notification = new App\Notifications\DocumentRequestApproved($docRequest);
    
    echo "Sending notification...\n";
    $docRequest->resident->notify($notification);
    
    echo "Email sent successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
