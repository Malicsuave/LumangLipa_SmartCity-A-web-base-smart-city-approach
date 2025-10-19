<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugDocumentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:document {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug specific document request';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        
        $this->info("=== DEBUGGING DOCUMENT REQUEST #{$id} ===");
        
        try {
            $request = \App\Models\DocumentRequest::with('resident')->find($id);
            
            if (!$request) {
                $this->error("Document request #{$id} not found!");
                return 1;
            }
            
            $this->info("Document ID: {$request->id}");
            $this->info("Document Type: {$request->document_type}");
            $this->info("Status: {$request->status}");
            $this->info("Purpose: {$request->purpose}");
            $this->info("Created: {$request->created_at}");
            $this->info("Approved: " . ($request->approved_at ?: 'Not approved'));
            
            if ($request->resident) {
                $this->info("\n=== RESIDENT DATA ===");
                $this->info("Resident ID: {$request->resident->id}");
                $this->info("Barangay ID: {$request->resident->barangay_id}");
                $this->info("Name: {$request->resident->first_name} {$request->resident->middle_name} {$request->resident->last_name}");
                $this->info("Address: {$request->resident->address}");
                $this->info("Purok: " . ($request->resident->purok ?: 'NULL'));
                $this->info("Occupation: " . ($request->resident->occupation ?: 'NULL'));
                $this->info("Profession: " . ($request->resident->profession_occupation ?: 'NULL'));
                $this->info("Monthly Income: " . ($request->resident->monthly_income ?: 'NULL'));
            } else {
                $this->error("No resident data found!");
            }
            
            $this->info("\n=== TESTING DOCUMENT GENERATION ===");
            
            if ($request->status !== 'approved') {
                $this->warn("Document is not approved! Status: {$request->status}");
                $this->info("Approving document for testing...");
                $request->update(['status' => 'approved', 'approved_at' => now()]);
            }
            
            try {
                $controller = new \App\Http\Controllers\DocumentGeneratorController();
                $response = $controller->generateDocument($request->id);
                $this->info("✓ Document generation: SUCCESS");
                $this->info("Response type: " . get_class($response));
                
                if (method_exists($response, 'getData')) {
                    $data = $response->getData();
                    $this->info("Template data keys: " . implode(', ', array_keys($data)));
                }
                
            } catch (\Exception $e) {
                $this->error("✗ Document generation FAILED!");
                $this->error("Error: " . $e->getMessage());
                $this->error("File: " . $e->getFile());
                $this->error("Line: " . $e->getLine());
                $this->error("Stack trace:");
                $this->error($e->getTraceAsString());
            }
            
        } catch (\Exception $e) {
            $this->error("Debug failed: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
