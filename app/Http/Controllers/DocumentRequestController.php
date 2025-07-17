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
    public function index(Request $request)
    {
        // Build query with search and filters
        $query = DocumentRequest::with(['resident', 'approver']);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_type', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('barangay_id', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($residentQuery) use ($search) {
                      $residentQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('middle_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Document type filter
        if ($request->has('document_type') && !empty($request->document_type)) {
            $query->where('document_type', $request->document_type);
        }
        
        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('requested_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('requested_at', '<=', $request->date_to);
        }
        
        // Sorting functionality
        $sortField = $request->get('sort', 'requested_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Define allowed sort fields for security
        $allowedSortFields = [
            'id', 'document_type', 'status', 'requested_at', 'barangay_id'
        ];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('requested_at', 'desc');
        }
        
        $documentRequests = $query->paginate(10);
        
        // Append query parameters to pagination links
        $documentRequests->appends($request->query());
        
        // Calculate metrics
        $stats = [
            'total' => DocumentRequest::count(),
            'pending' => DocumentRequest::where('status', 'pending')->count(),
            'approved' => DocumentRequest::where('status', 'approved')->count(),
            'claimed' => DocumentRequest::where('status', 'claimed')->count(),
            'rejected' => DocumentRequest::where('status', 'rejected')->count(),
        ];
        
        return view('admin.documents', compact('documentRequests', 'stats'));
    }

    public function create()
    {
        return view('public.forms.document-request');
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
            'resident_id' => $resident->id, // Set resident_id
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
        $documentRequest = DocumentRequest::with(['resident', 'approver', 'claimedBy'])->findOrFail($id);
        $response = $documentRequest->toArray();
        // Add claimed info if claimed
        if ($documentRequest->status === 'claimed') {
            $response['claimed_at'] = $documentRequest->claimed_at ? $documentRequest->claimed_at->format('Y-m-d H:i:s') : null;
            $response['claimed_by'] = $documentRequest->claimedBy ? $documentRequest->claimedBy->name : null;
        }
        return response()->json($response);
    }

    public function approve($id)
    {
        $documentRequest = DocumentRequest::with('resident')->findOrFail($id);
        
        $documentRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Send notification to resident with PDF attachment
        if ($documentRequest->resident && $documentRequest->resident->email_address) {
            try {
                $documentRequest->resident->notify(new \App\Notifications\DocumentRequestApproved($documentRequest));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send document approval notification', [
                    'document_request_id' => $documentRequest->id,
                    'resident_email' => $documentRequest->resident->email_address,
                    'error' => $e->getMessage()
                ]);
                
                // Continue with success response even if email fails
                // The document is still approved
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Document request approved successfully! An email with the PDF document has been sent to the resident.'
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

    /**
     * Mark document as claimed by resident
     */
    public function markAsClaimed($id)
    {
        $documentRequest = DocumentRequest::with('resident')->findOrFail($id);
        
        // Check if document is approved
        if ($documentRequest->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Only approved documents can be marked as claimed.'
            ], 422);
        }
        
        $documentRequest->update([
            'status' => 'claimed',
            'claimed_at' => now(),
            'claimed_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document marked as claimed successfully! The resident has personally collected the document.',
            'data' => [
                'document_id' => $documentRequest->id,
                'resident_name' => $documentRequest->resident->first_name . ' ' . $documentRequest->resident->last_name,
                'document_type' => $documentRequest->document_type,
                'claimed_at' => $documentRequest->claimed_at->format('Y-m-d H:i:s'),
                'claimed_by' => auth()->user()->name
            ]
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
