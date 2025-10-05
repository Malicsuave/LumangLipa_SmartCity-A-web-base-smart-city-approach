<?php
// Test script to debug QR submission issues

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Get a resident for testing
$residents = \App\Models\Resident::take(3)->get();

echo "Available residents for testing:\n";
foreach ($residents as $resident) {
    echo "ID: {$resident->barangay_id} - Name: {$resident->first_name} {$resident->last_name}\n";
}
echo "\n";

echo "Testing QR verification process:\n";

// Test the checkResident functionality
$testBarangayId = $residents->first()->barangay_id;
echo "Testing with Barangay ID: {$testBarangayId}\n";

$resident = \App\Models\Resident::where('barangay_id', $testBarangayId)->first();
if ($resident) {
    echo "✓ Resident found in database\n";
    echo "  Name: {$resident->first_name} {$resident->last_name}\n";
    echo "  Address: {$resident->address}\n";
    echo "  Contact: {$resident->contact_number}\n";
} else {
    echo "✗ Resident not found in database\n";
}

// Test validation rules
echo "\nTesting validation rules:\n";
$validationRules = [
    'barangay_id' => 'required|string|exists:residents,barangay_id',
    'document_type' => 'required|string|in:Barangay Clearance,Certificate of Residency,Certificate of Indigency,Certificate of Low Income,Business Permit',
    'purpose' => 'required|string|max:500',
    'verification_method' => 'required|string|in:manual,qr',
    'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
];

echo "Validation rules configured:\n";
foreach ($validationRules as $field => $rule) {
    echo "  {$field}: {$rule}\n";
}

echo "\nNote: 'receipt' field requires file upload - this might be the issue for QR submissions\n";