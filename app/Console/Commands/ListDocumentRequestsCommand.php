<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentRequest;

class ListDocumentRequestsCommand extends Command
{
    protected $signature = 'list:document-requests';
    protected $description = 'List all document requests';

    public function handle()
    {
        $requests = DocumentRequest::with('resident')->get();
        
        if ($requests->isEmpty()) {
            $this->info('No document requests found.');
            return 0;
        }

        $this->info('Document Requests:');
        $this->info('================');
        
        foreach ($requests as $request) {
            $resident = $request->resident;
            $residentName = $resident ? "{$resident->first_name} {$resident->last_name}" : 'No resident';
            
            $this->info("ID: {$request->id}");
            $this->info("Type: {$request->document_type}");
            $this->info("Resident: {$residentName}");
            $this->info("Status: {$request->status}");
            $this->info("Purpose: {$request->purpose}");
            $this->info("Created: {$request->created_at}");
            $this->info("---");
        }

        return 0;
    }
}
