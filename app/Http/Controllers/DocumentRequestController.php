<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use App\Models\Resident;
use App\Services\OtpService;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DocumentRequestController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function index()
    {
        $documentRequests = DocumentRequest::with(['resident', 'approver'])
            ->orderBy('requested_at', 'desc')
            ->paginate(10);
        
        return view('admin.documents', compact('documentRequests'));
    }

    public function create()
    {
        return view('documents.request');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|string|exists:residents,barangay_id',
            'document_type' => 'required|string|in:Barangay Clearance,Certificate of Residency,Certificate of Indigency,Certificate of Low Income,Business Permit',
            'purpose' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if resident exists
        $resident = Resident::where('barangay_id', $request->barangay_id)->first();
        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'Resident not found with the provided Barangay ID.'
            ], 404);
        }

        // Check if OTP has been verified
        if (!$this->otpService->hasValidOtp($request->barangay_id, 'document_verification')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your identity with the OTP sent to your email before submitting the request.'
            ], 422);
        }

        // Create document request
        $documentRequest = DocumentRequest::create([
            'barangay_id' => $request->barangay_id,
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document request submitted successfully!',
            'data' => [
                'request_id' => $documentRequest->id,
                'resident_name' => $resident->first_name . ' ' . $resident->last_name,
                'document_type' => $documentRequest->document_type,
                'status' => $documentRequest->status
            ]
        ]);
    }

    public function show($id)
    {
        $documentRequest = DocumentRequest::with(['resident', 'approver'])->findOrFail($id);
        return response()->json($documentRequest);
    }

    public function approve($id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);
        
        $documentRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document request approved successfully!'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $documentRequest = DocumentRequest::findOrFail($id);
        
        $documentRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document request rejected successfully!'
        ]);
    }

    public function checkResident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $resident = Resident::where('barangay_id', $request->barangay_id)->first();
        
        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'No resident found with the provided Barangay ID.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'resident' => [
                'name' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
                'address' => $resident->address,
                'barangay_id' => $resident->barangay_id,
                'age' => $resident->birthdate ? Carbon::parse($resident->birthdate)->age : 'N/A',
                'contact_number' => $resident->contact_number,
            ]
        ]);
    }

    /**
     * Send OTP for verification
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->otpService->generateAndSendOtp($request->barangay_id, 'document_verification');

        return response()->json($result);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|string',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->otpService->verifyOtp($request->barangay_id, $request->otp_code, 'document_verification');

        if ($result['success']) {
            // Also return resident info for the form
            $resident = Resident::where('barangay_id', $request->barangay_id)->first();
            $result['resident'] = [
                'name' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
                'address' => $resident->address,
                'barangay_id' => $resident->barangay_id,
                'age' => $resident->birthdate ? Carbon::parse($resident->birthdate)->age : 'N/A',
                'contact_number' => $resident->contact_number,
            ];
        }

        return response()->json($result);
    }
}
