<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthServiceRequest;
use App\Models\Resident;
use App\Models\HealthMeeting;
use App\Services\OtpService;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HealthServiceController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function adminDashboard()
    {
        // Get statistics for the dashboard
        $totalRequests = HealthServiceRequest::count();
        $pendingRequests = HealthServiceRequest::where('status', 'pending')->count();
        $completedRequests = HealthServiceRequest::where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->count();
        
        // Get recent requests for display
        $recentRequests = HealthServiceRequest::with('resident')
            ->orderBy('requested_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.health', compact('totalRequests', 'pendingRequests', 'completedRequests', 'recentRequests'));
    }

    public function index(Request $request)
    {
        $query = HealthServiceRequest::with(['resident', 'approver']);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhere('barangay_id', 'like', "%{$search}%")
                    ->orWhereHas('resident', function ($residentQuery) use ($search) {
                        $residentQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }
        
        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Service type filter
        if ($request->has('service_type') && !empty($request->service_type)) {
            $query->where('service_type', $request->service_type);
        }
        
        // Date range filters
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
            'id', 'service_type', 'status', 'requested_at', 'barangay_id'
        ];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('requested_at', 'desc');
        }

        $healthRequests = $query->paginate(10);
        
        // Append query parameters to pagination links
        $healthRequests->appends($request->query());

        return view('admin.health-services', compact('healthRequests'));
    }

    public function create()
    {
        $serviceTypes = [
            'medical_consultation' => 'Medical Consultation',
            'blood_pressure_check' => 'Blood Pressure Check',
            'vaccination' => 'Vaccination',
            'prenatal_checkup' => 'Prenatal Checkup',
            'health_certificate' => 'Health Certificate',
            'medicine_distribution' => 'Medicine Distribution',
            'first_aid' => 'First Aid',
            'health_education' => 'Health Education',
            'other' => 'Other Health Service'
        ];

        return view('public/forms/health-request', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|string|exists:residents,barangay_id',
            'service_type' => 'required|string',
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
        if (!$this->otpService->hasValidOtp($request->barangay_id, 'health_verification')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your identity with the OTP sent to your email before submitting the request.'
            ], 422);
        }

        // Create health service request
        $healthRequest = HealthServiceRequest::create([
            'barangay_id' => $request->barangay_id,
            'service_type' => $request->service_type,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health service request submitted successfully!',
            'data' => [
                'request_id' => $healthRequest->id,
                'resident_name' => $resident->first_name . ' ' . $resident->last_name,
                'service_type' => $healthRequest->service_type,
                'status' => $healthRequest->status
            ]
        ]);
    }

    public function show($id)
    {
        $healthRequest = HealthServiceRequest::with(['resident', 'approver'])->findOrFail($id);
        return response()->json($healthRequest);
    }

    public function approve($id)
    {
        $healthRequest = HealthServiceRequest::findOrFail($id);
        
        $healthRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health service request approved successfully!'
        ]);
    }

    public function complete($id)
    {
        $healthRequest = HealthServiceRequest::findOrFail($id);
        
        $healthRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health service request completed successfully!'
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

        $healthRequest = HealthServiceRequest::findOrFail($id);
        
        $healthRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health service request rejected successfully!'
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
                'age' => $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->age : 'N/A',
                'contact_number' => $resident->contact_number,
            ]
        ]);
    }

    public function scheduleMeeting(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date|after:now',
            'meeting_location' => 'required|string|max:255',
            'meeting_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $healthRequest = HealthServiceRequest::findOrFail($id);
        
        // Update health service request status to scheduled
        $healthRequest->update([
            'status' => 'scheduled',
            'scheduled_at' => $request->meeting_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Health service meeting scheduled successfully!'
        ]);
    }

    /**
     * Send OTP for health service verification
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

        $result = $this->otpService->generateAndSendOtp($request->barangay_id, 'health_verification');

        return response()->json($result);
    }

    /**
     * Verify OTP for health service
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

        $result = $this->otpService->verifyOtp($request->barangay_id, $request->otp_code, 'health_verification');

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
