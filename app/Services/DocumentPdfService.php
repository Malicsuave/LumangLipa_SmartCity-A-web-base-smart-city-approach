<?php

namespace App\Services;

use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class DocumentPdfService
{
    /**
     * Generate QR code for document verification
     */
    private function generateQrCode($uuid)
    {
        $verificationUrl = url('/verify/' . $uuid);
        $renderer = new ImageRenderer(
            new RendererStyle(150),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrPng = base64_encode($writer->writeString($verificationUrl));
        return $qrPng;
    }

    /**
     * Generate PDF content for a document request using Snappy PDF
     */
    public function generatePdfContent(DocumentRequest $documentRequest): string
    {
        try {
            $resident = $documentRequest->resident;
            
            if (!$resident) {
                throw new \Exception('Resident not found for document request');
            }
            
            // Get officials data for the documents
            $officials = $this->getOfficialsForDocuments();
            
            if (!$officials) {
                throw new \Exception('Barangay officials data not found. Please ensure officials information is configured in the system.');
            }
            
            // Generate QR code for document verification
            $qrCode = $this->generateQrCode($documentRequest->uuid);
            
            // Prepare common data for document generation
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
                'isPrintMode' => true, // Use print mode for PDF generation to match preview/print design
                'officials' => $officials, // Add officials data for templates
                'qrCode' => $qrCode, // Add QR code for verification
            ];
            
            // Get the appropriate view template based on document type
            $viewTemplate = $this->getViewTemplate($documentRequest->document_type, $data);

            // Render Blade view to HTML so we can inspect it and pass raw HTML to Snappy
            $html = view($viewTemplate['view'], $viewTemplate['data'])->render();
            
            if (empty(trim($html))) {
                throw new \Exception('Rendered HTML is empty for view: ' . $viewTemplate['view']);
            }

            // Write rendered HTML to a temporary file and load it with Snappy
            $tempHtml = tempnam(sys_get_temp_dir(), 'doc_html_') . '_' . time() . '_' . uniqid() . '.html';
            if (file_put_contents($tempHtml, $html) === false) {
                throw new \Exception('Failed to write temporary HTML file for PDF generation');
            }

            $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadFile($tempHtml);
            
            // Set PDF options optimized to match browser print preview
            $pdf->setOptions([
                // Standard letter size with minimal margins
                'page-size' => 'Letter',
                'orientation' => 'Portrait',
                'margin-top' => '0mm',
                'margin-right' => '0mm',
                'margin-bottom' => '0mm',
                'margin-left' => '0mm',
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
                'disable-smart-shrinking' => true, // Prevents auto-shrinking
                'enable-smart-shrinking' => false, // Double ensure no shrinking
                'dpi' => 96, // Match browser DPI
                'image-dpi' => 96, // Match browser image DPI
                'image-quality' => 100, // High quality images
                'zoom' => 1.0, // 100% zoom to match preview exactly
                'load-error-handling' => 'ignore',
                'load-media-error-handling' => 'ignore',
                'enable-external-links' => false,
                'enable-internal-links' => false,
                'print-media-type' => true, // CRITICAL: Use @media print CSS rules
                'no-background' => false,
                'javascript-delay' => 100,
                'no-stop-slow-scripts' => true,
                'debug-javascript' => false,
                'lowquality' => false, // Ensure high quality output
                'cache-dir' => sys_get_temp_dir() . '/wkhtmltopdf_cache_' . time(), // Unique cache dir to prevent caching
            ]);
            
            // Generate PDF content
            $pdfContent = $pdf->output();
            
            // Clean up temporary HTML file
            if (isset($tempHtml) && file_exists($tempHtml)) {
                @unlink($tempHtml);
            }
            
            return $pdfContent;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF content', [
                'document_request_id' => $documentRequest->id,
                'document_type' => $documentRequest->document_type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Unable to generate PDF document: ' . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Get the appropriate view template and data for the document type
     */
    private function getViewTemplate(string $documentType, array $baseData): array
    {
        switch ($documentType) {
            case 'Barangay Clearance':
                return [
                    'view' => 'documents.templates.barangay-clearance',
                    'data' => $baseData
                ];
                
            case 'Certificate of Residency':
                return [
                    'view' => 'documents.templates.certificate-of-residency-fixed',
                    'data' => array_merge($baseData, [
                        'purok' => $baseData['resident']->purok ?? 'N/A',
                    ])
                ];
                
            case 'Certificate of Indigency':
                return [
                    'view' => 'documents.templates.certificate-of-indigency-original',
                    'data' => $baseData
                ];
                
            case 'Certificate of Low Income':
                return [
                    'view' => 'documents.templates.certificate-of-low-income-original',
                    'data' => array_merge($baseData, [
                        'purok' => $baseData['resident']->purok ?? 'N/A',
                        'income' => $baseData['resident']->monthly_income ?? '',
                        'occupation' => $baseData['resident']->occupation ?? '',
                    ])
                ];
                  case 'Certificate of No/Low Income':
                return [
                    'view' => 'documents.templates.cerficate-of-no-income',
                    'data' => $baseData
                ];
                
            case 'Certificate of Relationship':
                return [
                    'view' => 'documents.templates.certificate-of-relationship',
                    'data' => array_merge($baseData, [
                        'purok' => $baseData['resident']->purok ?? 'N/A',
                    ])
                ];
                
            default:
                throw new \Exception("Unsupported document type: {$documentType}");
        }
    }
    
    /**
     * Create a temporary PDF file and return its path
     */
    public function createTempPdfFile(string $pdfContent): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'barangay_document_') . '.pdf';
        
        if (file_put_contents($tempFile, $pdfContent) === false) {
            throw new \Exception('Failed to create temporary PDF file');
        }
        
        return $tempFile;
    }
    
    /**
     * Generate filename for the document
     */
    public function generateFileName(DocumentRequest $documentRequest): string
    {
        $resident = $documentRequest->resident;
        $lastName = strtoupper(str_replace(' ', '_', $resident->last_name));
        $firstName = strtoupper(str_replace(' ', '_', $resident->first_name));
        $documentType = str_replace([' ', '/'], '_', strtoupper($documentRequest->document_type));
        $date = $documentRequest->approved_at->format('Y-m-d');
        
        return "{$documentType}_{$lastName}_{$firstName}_{$date}.pdf";
    }
    
    /**
     * Get officials data in the format expected by document templates
     */
     public function getOfficialsForDocuments()
    {
        // Use the officials table (not barangay_officials)
        $officials = \App\Models\Official::all();
        
        $result = (object) [
            'captain_name' => 'N/A',
            'secretary_name' => 'N/A',
            'treasurer_name' => 'N/A',
            'sk_chairperson_name' => 'N/A',
            'sk_chairperson_committee' => '',
        ];
        
        // Add councilor fields
        for ($i = 1; $i <= 7; $i++) {
            $result->{"councilor{$i}_name"} = 'N/A';
            $result->{"councilor{$i}_committee"} = '';
        }
        
        $councilors = [];
        
        foreach ($officials as $official) {
            switch ($official->position) {
                case 'Captain':
                    $result->captain_name = $official->name;
                    break;
                case 'Secretary':
                    $result->secretary_name = $official->name;
                    break;
                case 'Treasurer':
                    $result->treasurer_name = $official->name;
                    break;
                case 'SK Chairman':
                    $result->sk_chairperson_name = $official->name;
                    $result->sk_chairperson_committee = $official->committee;
                    break;
                case 'Councilor':
                    $councilors[] = $official;
                    break;
            }
        }
        
        // Assign councilors to numbered slots (preserve database order)
        for ($i = 0; $i < min(7, count($councilors)); $i++) {
            $slotNumber = $i + 1;
            $result->{"councilor{$slotNumber}_name"} = $councilors[$i]->name;
            $result->{"councilor{$slotNumber}_committee"} = $councilors[$i]->committee ?? '';
        }
        
        return $result;
    }
}