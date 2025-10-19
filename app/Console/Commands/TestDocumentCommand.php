<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDocumentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test document generation functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $request = \App\Models\DocumentRequest::with('resident')->first();
        
        if (!$request) {
            $this->error('No document requests found');
            return 1;
        }
        
        $this->info("Document Request ID: {$request->id}");
        $this->info("Document Type: {$request->document_type}");
        $this->info("Resident: {$request->resident->first_name} {$request->resident->last_name}");
        $this->info("Status: {$request->status}");
        
        // Test the generator
        try {
            $controller = new \App\Http\Controllers\DocumentGeneratorController();
            $response = $controller->generateDocument($request->id);
            $this->info("Document generation: SUCCESS");
            $this->info("Response type: " . get_class($response));
        } catch (\Exception $e) {
            $this->error("Document generation failed: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . " Line: " . $e->getLine());
        }
        
        return 0;
    }
}
