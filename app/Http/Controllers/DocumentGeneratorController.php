<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\Resident;
use App\Models\BarangayOfficial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class DocumentGeneratorController extends Controller
{
    public function generateDocument($documentRequestId, Request $request)
    {
        try {
            $documentRequest = DocumentRequest::with('resident')->findOrFail($documentRequestId);
            $resident = $documentRequest->resident;

            if (!$resident) {
                abort(404, 'Resident not found');
            }

            // Check if document is approved
            if ($documentRequest->status !== 'approved') {
                abort(403, 'Document must be approved before viewing');
            }

            // Determine if this is for printing or viewing
            $isPrintMode = $request->route()->getName() === 'admin.documents.print';

            // Generate the document based on type
            switch ($documentRequest->document_type) {
                case 'Barangay Clearance':
                    return $this->generateBarangayClearance($documentRequest, $resident, $isPrintMode);
                case 'Certificate of Residency':
                    return $this->generateResidencyCertificate($documentRequest, $resident, $isPrintMode);
                case 'Certificate of Indigency':
                    return $this->generateIndigencyCertificate($documentRequest, $resident, $isPrintMode);
                case 'Certificate of Low Income':
                    return $this->generateLowIncomeCertificate($documentRequest, $resident, $isPrintMode);
                case 'Certificate of No/Low Income':
                    return $this->generateNoLowIncomeCertificate($documentRequest, $resident, $isPrintMode);
                case 'Certificate of Relationship':
                    return $this->generateRelationshipCertificate($documentRequest, $resident, $isPrintMode);
                default:
                    abort(404, 'Document type not supported: ' . $documentRequest->document_type);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Document generation failed', [
                'documentRequestId' => $documentRequestId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return a user-friendly error
            abort(500, 'Unable to generate document. Please try again or contact administrator.');
        }
    }

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

    private function generateBarangayClearance($documentRequest, $resident, $isPrintMode = false)
    {
        $officials = app(\App\Services\DocumentPdfService::class)->getOfficialsForDocuments();
        $qrCode = $this->generateQrCode($documentRequest->uuid);
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
            'isPrintMode' => $isPrintMode,
            'officials' => $officials,
            'qrCode' => $qrCode,
        ];
        return view('documents.templates.barangay-clearance', $data);
    }

    private function generateResidencyCertificate($documentRequest, $resident, $isPrintMode = false)
    {
        $officials = app(\App\Services\DocumentPdfService::class)->getOfficialsForDocuments();
        $qrCode = $this->generateQrCode($documentRequest->uuid);
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
            'isPrintMode' => $isPrintMode,
            'officials' => $officials,
            'qrCode' => $qrCode,
        ];
        return view('documents.templates.certificate-of-residency-fixed', $data);
    }

    private function generateIndigencyCertificate($documentRequest, $resident, $isPrintMode = false)
    {
        $officials = app(\App\Services\DocumentPdfService::class)->getOfficialsForDocuments();
        $qrCode = $this->generateQrCode($documentRequest->uuid);
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
            'isPrintMode' => $isPrintMode,
            'officials' => $officials,
            'qrCode' => $qrCode,
        ];
        return view('documents.templates.certificate-of-indigency-original', $data);
    }

    private function generateLowIncomeCertificate($documentRequest, $resident, $isPrintMode = false)
    {
        $officials = app(\App\Services\DocumentPdfService::class)->getOfficialsForDocuments();
        $qrCode = $this->generateQrCode($documentRequest->uuid);
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
            'income' => $documentRequest->monthly_income ?? 'N/A',
            'occupation' => $documentRequest->occupation ?? 'N/A',
            'isPrintMode' => $isPrintMode,
            'officials' => $officials,
            'qrCode' => $qrCode,
        ];
        return view('documents.templates.certificate-of-low-income-original', $data);
    }

    private function generateNoLowIncomeCertificate($documentRequest, $resident, $isPrintMode = false)
    {
        $officials = app(\App\Services\DocumentPdfService::class)->getOfficialsForDocuments();
        $qrCode = $this->generateQrCode($documentRequest->uuid);
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
            'isPrintMode' => $isPrintMode,
            'officials' => $officials,
            'qrCode' => $qrCode,
        ];
        return view('documents.templates.cerficate-of-no-income', $data);
    }

    private function generateRelationshipCertificate($documentRequest, $resident, $isPrintMode = false)
    {
        $officials = app(\App\Services\DocumentPdfService::class)->getOfficialsForDocuments();
        $qrCode = $this->generateQrCode($documentRequest->uuid);
        $data = [
            'resident' => $resident,
            'documentRequest' => $documentRequest,
            'fullName' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
            'age' => Carbon::parse($resident->birthdate)->age,
            'civilStatus' => $resident->civil_status,
            'address' => $resident->current_address ?? 'N/A',
            'purpose' => $documentRequest->purpose,
            'dateIssued' => $documentRequest->approved_at ? $documentRequest->approved_at : now(),
            'barangayId' => $resident->barangay_id,
            'purok' => $resident->purok ?? 'N/A',
            'isPrintMode' => $isPrintMode,
            'officials' => $officials,
            'qrCode' => $qrCode,
        ];
        return view('documents.templates.certificate-of-relationship', $data);
    }
}
