<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckDocumentTypesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:document-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all available document types in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $documentTypes = \App\Models\DocumentRequest::select('document_type')->distinct()->pluck('document_type');
        
        $this->info("Available Document Types:");
        foreach($documentTypes as $type) {
            $count = \App\Models\DocumentRequest::where('document_type', $type)->count();
            $this->line("- {$type} ({$count} requests)");
        }
        
        // Test each type
        foreach($documentTypes as $type) {
            $request = \App\Models\DocumentRequest::with('resident')
                ->where('document_type', $type)
                ->where('status', 'approved')
                ->first();
                
            if ($request) {
                $this->info("\nTesting {$type}...");
                try {
                    $controller = new \App\Http\Controllers\DocumentGeneratorController();
                    $response = $controller->generateDocument($request->id);
                    $this->info("✓ {$type}: SUCCESS");
                } catch (\Exception $e) {
                    $this->error("✗ {$type}: FAILED - " . $e->getMessage());
                }
            } else {
                $this->warn("⚠ {$type}: No approved requests to test");
            }
        }
        
        return 0;
    }
}
