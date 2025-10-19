<?php

namespace App\Services;

use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DocumentPdfServiceWithQr
{
    /**
     * Generate QR code for document verification
     */
    private function generateQrCode(DocumentRequest $documentRequest): string
    {
        // Create verification data for QR code
        $qrData = json_encode([
            'document_id' => $documentRequest->id,
            'document_type' => $documentRequest->document_type,
            'resident_name' => $documentRequest->resident->first_name . ' ' . $documentRequest->resident->last_name,
            'issued_date' => $documentRequest->approved_at ? $documentRequest->approved_at->format('Y-m-d') : now()->format('Y-m-d'),
            'barangay_id' => $documentRequest->resident->barangay_id,
            'verification_url' => url("/verify-document/{$documentRequest->id}"),
            'hash' => hash('sha256', $documentRequest->id . $documentRequest->resident->barangay_id . $documentRequest->document_type)
        ]);
        
        try {
            // Try SimpleSoftwareIO QR Code first
            if (class_exists('SimpleSoftwareIO\QrCode\Facades\QrCode')) {
                $qrCodePng = app('SimpleSoftwareIO\QrCode\Generator')->format('png')
                    ->size(300)
                    ->margin(2)
                    ->errorCorrection('M')
                    ->generate($qrData);
                return base64_encode($qrCodePng);
            }
            
            // Try basic QR code generation with online service as fallback
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);
            $qrCodePng = file_get_contents($qrUrl);
            if ($qrCodePng !== false) {
                return base64_encode($qrCodePng);
            }
            
            throw new \Exception('No QR code generation method available');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code', [
                'document_request_id' => $documentRequest->id,
                'error' => $e->getMessage()
            ]);
            
            // Return empty string if QR generation fails
            return '';
        }
    }
    
    /**
     * Generate PDF content with QR code
     */
    public function generatePdfContentWithQr(DocumentRequest $documentRequest): string
    {
        $originalService = new DocumentPdfService();
        
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
            
            // Generate QR code
            $qrCode = $this->generateQrCode($documentRequest);
            
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
                'isPrintMode' => false,
                'officials' => $officials,
                'qrCode' => $qrCode, // Add QR code to data
            ];
            
            // Get the appropriate view template based on document type
            $viewTemplate = $this->getViewTemplate($documentRequest->document_type, $data);
            
            // Generate PDF using Snappy with print-optimized settings
            $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView($viewTemplate['view'], $viewTemplate['data']);
            
            // Set PDF options
            $pdf->setOptions([
                // Use same reduced page as standard service
                'page-width' => '7.5in',
                'page-height' => '9in',
                'orientation' => 'Portrait',
                'margin-top' => '0.15in',
                'margin-right' => '0.15in',
                'margin-bottom' => '0.10in',
                'margin-left' => '0.15in',
                'encoding' => 'UTF-8',
                'enable-local-file-access' => true,
                'disable-smart-shrinking' => true,
                'dpi' => 300,
                'image-quality' => 100,
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
            
            // Generate and return PDF content
            return $pdf->output();
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF content with QR', [
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
