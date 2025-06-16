<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
                case 'Business Permit':
                    return $this->generateBusinessPermit($documentRequest, $resident, $isPrintMode);
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

    private function generateBarangayClearance($documentRequest, $resident, $isPrintMode = false)
    {
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
        ];

        return view('documents.templates.barangay-clearance', $data);
    }

    private function generateResidencyCertificate($documentRequest, $resident, $isPrintMode = false)
    {
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
        ];

        return view('documents.templates.certificate-of-residency-fixed', $data);
    }

    private function generateIndigencyCertificate($documentRequest, $resident, $isPrintMode = false)
    {
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
        ];

        return view('documents.templates.certificate-of-indigency-original', $data);
    }

    private function generateLowIncomeCertificate($documentRequest, $resident, $isPrintMode = false)
    {
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
            'isPrintMode' => $isPrintMode,
        ];

        return view('documents.templates.certificate-of-low-income-original', $data);
    }

    private function generateBusinessPermit($documentRequest, $resident, $isPrintMode = false)
    {
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
            'businessName' => $documentRequest->business_name ?? 'N/A',
            'businessAddress' => $documentRequest->business_address ?? $resident->address,
            'isPrintMode' => $isPrintMode,
        ];

        return view('documents.templates.business-permit', $data);
    }
}
