<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use App\Models\DocumentRequest;
use App\Models\Resident;
use Carbon\Carbon;

class TestBladeRenderCommand extends Command
{
    protected $signature = 'test:blade-render {document_request_id}';
    protected $description = 'Test Blade template rendering for a specific document request';

    public function handle()
    {
        $documentRequestId = $this->argument('document_request_id');
        
        try {
            $documentRequest = DocumentRequest::with('resident')->findOrFail($documentRequestId);
            $resident = $documentRequest->resident;

            if (!$resident) {
                $this->error('Resident not found');
                return 1;
            }

            $this->info("Testing document: {$documentRequest->document_type}");
            $this->info("Resident: {$resident->first_name} {$resident->last_name}");

            $data = [
                'resident' => $resident,
                'documentRequest' => $documentRequest,
                'fullName' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
                'age' => Carbon::parse($resident->birthdate)->age,
                'civilStatus' => $resident->civil_status,
                'address' => $resident->address,
                'purpose' => $documentRequest->purpose,
                'dateIssued' => $documentRequest->approved_at ? $documentRequest->approved_at : now(),
                'barangayId' => $resident->barangay_id,
                'purok' => $resident->purok ?? 'N/A',
                'income' => $resident->monthly_income ?? 'N/A',
                'occupation' => $resident->occupation ?? 'N/A',
            ];            // Test the specific template based on document type
            switch ($documentRequest->document_type) {
                case 'Certificate of Residency':
                    $template = 'documents.templates.certificate-of-residency-fixed';
                    break;
                case 'Certificate of Indigency':
                    $template = 'documents.templates.certificate-of-indigency-fixed';
                    break;
                case 'Certificate of Low Income':
                    $template = 'documents.templates.certificate-of-low-income-fixed';
                    break;
                case 'Barangay Clearance':
                    $template = 'documents.templates.barangay-clearance';
                    break;
                case 'Business Permit':
                    $template = 'documents.templates.business-permit';
                    break;
                default:
                    $this->error('Unsupported document type');
                    return 1;
            }

            $this->info("Testing template: {$template}");

            // Check if view exists
            if (!View::exists($template)) {
                $this->error("View does not exist: {$template}");
                return 1;
            }            // Try to render the view
            $this->info('Attempting to render view...');
            
            try {
                $view = view($template, $data);
                $this->info('View object created successfully');
                
                $rendered = $view->render();
                $this->info('View rendered, checking content...');
                
                // Check if rendered content is empty or just whitespace
                if (trim($rendered) === '') {
                    $this->error('Rendered content is empty!');
                    $this->info('Raw content length: ' . strlen($rendered));
                    return 1;
                } else {
                    $this->info('Content has ' . strlen(trim($rendered)) . ' non-whitespace characters');
                }
            } catch (\Exception $renderException) {
                $this->error('Error during view rendering: ' . $renderException->getMessage());
                $this->error('Render file: ' . $renderException->getFile());
                $this->error('Render line: ' . $renderException->getLine());
                return 1;
            }

            $this->info('Template rendered successfully!');
            $this->info('Content length: ' . strlen($rendered) . ' characters');
            
            // Show first 200 characters to verify content
            $preview = substr(strip_tags($rendered), 0, 200);
            $this->info('Content preview: ' . $preview . '...');

            return 0;

        } catch (\Exception $e) {
            $this->error('Error rendering template: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
