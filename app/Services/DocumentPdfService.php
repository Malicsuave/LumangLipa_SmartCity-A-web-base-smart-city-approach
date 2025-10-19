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
            
            // Get barangay officials data
            $officials = \App\Models\BarangayOfficial::first();
            
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
                'isPrintMode' => false, // Don't show print button in PDF
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
            $tempHtml = tempnam(sys_get_temp_dir(), 'doc_html_') . '.html';
            if (file_put_contents($tempHtml, $html) === false) {
                throw new \Exception('Failed to write temporary HTML file for PDF generation');
            }

            $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadFile($tempHtml);
            
            // Set PDF options to match the certificate print format exactly
            $pdf->setOptions([
                // Use a smaller custom page size and even margins
                'page-width' => '7.5in',
                'page-height' => '9in',
                'orientation' => 'Portrait',
                'margin-top' => '0.15in',
                'margin-right' => '0.15in',
                'margin-bottom' => '0.10in', // tightened to match visual balance
                'margin-left' => '0.15in',
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
                'disable-smart-shrinking' => true,
                'dpi' => 300,
                'image-quality' => 100,
                // Scale down content to fit the reduced page
                'zoom' => 0.88,
                'load-error-handling' => 'ignore',
                'load-media-error-handling' => 'ignore',
                'enable-external-links' => false,
                'enable-internal-links' => false,
                'print-media-type' => true,
                'no-background' => false,
                'javascript-delay' => 1000,
                'no-stop-slow-scripts' => true,
                'debug-javascript' => false,
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
                        'income' => $baseData['resident']->monthly_income ?? 'N/A',
                        'occupation' => $baseData['resident']->occupation ?? 'N/A',
                    ])
                ];
                
            case 'Business Permit':
                return [
                    'view' => 'documents.templates.business-permit',
                    'data' => array_merge($baseData, [
                        'businessName' => $baseData['documentRequest']->business_name ?? 'N/A',
                        'businessAddress' => $baseData['documentRequest']->business_address ?? $baseData['resident']->address,
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
}