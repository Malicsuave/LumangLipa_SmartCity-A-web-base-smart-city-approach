<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentRequest;
use App\Models\Resident;

class DocumentRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first resident to create sample requests
        $resident = Resident::first();
        
        if (!$resident) {
            echo "No residents found. Please create residents first.\n";
            return;
        }

        $sampleRequests = [
            [
                'barangay_id' => $resident->barangay_id,
                'document_type' => 'Barangay Clearance',
                'purpose' => 'For employment requirements at ABC Company. Needed for background check and verification purposes.',
                'status' => 'pending',
                'requested_at' => now()->subDays(2),
            ],
            [
                'barangay_id' => $resident->barangay_id,
                'document_type' => 'Certificate of Residency',
                'purpose' => 'For school enrollment at XYZ University. Required document for new student registration.',
                'status' => 'approved',
                'requested_at' => now()->subDays(5),
                'approved_at' => now()->subDays(3),
                'approved_by' => 1, // Assuming user ID 1 exists
            ],
            [
                'barangay_id' => $resident->barangay_id,
                'document_type' => 'Certificate of Indigency',
                'purpose' => 'For medical assistance application. Needed to avail of government healthcare subsidies.',
                'status' => 'approved',
                'requested_at' => now()->subDays(7),
                'approved_at' => now()->subDays(4),
                'approved_by' => 1,
            ],
        ];

        foreach ($sampleRequests as $request) {
            DocumentRequest::create($request);
        }

        echo "Sample document requests created successfully!\n";
    }
}
