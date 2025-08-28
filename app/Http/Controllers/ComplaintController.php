<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\Resident;
use App\Models\ComplaintMeeting;
use App\Services\OtpService;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function adminDashboard()
    {
        // Get statistics for the dashboard
        $totalComplaints = Complaint::count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $resolvedComplaints = Complaint::where('status', 'resolved')
            ->whereMonth('resolved_at', now()->month)
            ->count();

        // Get recent complaints for display
        $recentComplaints = Complaint::with('resident')
            ->orderBy('filed_at', 'desc')
            ->paginate(5);

        return view('admin.complaints', compact('totalComplaints', 'pendingComplaints', 'resolvedComplaints', 'recentComplaints'));
    }

    public function index(Request $request)
    {
        $query = Complaint::with(['resident', 'approver']);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
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
        
        // Type filter
        if ($request->has('type') && !empty($request->type)) {
            $query->where('complaint_type', $request->type);
        }
        
        // Date range filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('filed_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('filed_at', '<=', $request->date_to);
        }
        
        // Sorting functionality
        $sortField = $request->get('sort', 'filed_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Define allowed sort fields for security
        $allowedSortFields = [
            'id', 'subject', 'complaint_type', 'status', 'filed_at', 'barangay_id'
        ];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('filed_at', 'desc');
        }

        $complaints = $query->paginate(10);
        
        // Append query parameters to pagination links
        $complaints->appends($request->query());

        return view('admin.complaint-management', compact('complaints'));
    }

    public function create()
    {
        $complaintTypes = [
            'noise_complaint' => 'Noise Complaint',
            'property_dispute' => 'Property Dispute',
            'public_safety' => 'Public Safety Issue',
            'infrastructure_problem' => 'Infrastructure Problem',
            'illegal_construction' => 'Illegal Construction',
            'waste_management' => 'Waste Management',
            'water_supply' => 'Water Supply Issue',
            'drainage_problem' => 'Drainage Problem',
            'animal_concern' => 'Animal/Pet Concern',
            'neighbor_dispute' => 'Neighbor Dispute',
            'traffic_concern' => 'Traffic Concern',
            'other' => 'Other Complaint'
        ];

        return view('public/forms/complaint-request', compact('complaintTypes'));
    }    public function store(Request $request)
    {        $validator = Validator::make($request->all(), [
            'barangay_id' => 'required|string|exists:residents,barangay_id',
            'complaint_type' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
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
        if (!$this->otpService->hasValidOtp($request->barangay_id, 'complaint_verification')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your identity with the OTP sent to your email before submitting the complaint.'
            ], 422);
        }

        // Create complaint
        $complaint = Complaint::create([
            'barangay_id' => $request->barangay_id,
            'complaint_type' => $request->complaint_type,
            'subject' => $request->subject,
            'description' => $request->description,
            'incident_details' => $request->incident_details,
            'incident_date' => $request->incident_date,
            'incident_location' => $request->incident_location,
            'involved_parties' => $request->involved_parties ? array_filter(explode("\n", $request->involved_parties)) : null,
            'status' => 'pending',
            'filed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint filed successfully!',
            'data' => [
                'complaint_id' => $complaint->id,
                'complainant_name' => $resident->first_name . ' ' . $resident->last_name,
                'complaint_type' => $complaint->complaint_type,
                'status' => $complaint->status
            ]
        ]);
    }

    public function show($id)
    {
        $complaint = Complaint::with(['resident', 'approver'])->findOrFail($id);
        return response()->json($complaint);
    }

    public function approve($id)
    {
        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint approved successfully!'
        ]);
    }

    public function resolve($id)
    {
        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint resolved successfully!'
        ]);
    }

    public function dismiss(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'dismissal_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'status' => 'dismissed',
            'dismissal_reason' => $request->dismissal_reason,
            'approved_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint dismissed successfully!'
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
                'message' => 'No resident found with this Barangay ID'
            ], 404);
        }        return response()->json([
            'success' => true,
            'resident' => [
                'name' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
                'address' => $resident->address,
                'age' => $resident->birthdate ? Carbon::parse($resident->birthdate)->age : 'N/A',
                'contact_number' => $resident->contact_number,
            ]
        ]);
    }

    public function schedule(Request $request, $id)
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

        $complaint = Complaint::findOrFail($id);

        // Update complaint status to scheduled
        $complaint->update([
            'status' => 'scheduled',
            'scheduled_at' => $request->meeting_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint meeting scheduled successfully!'
        ]);
    }

    /**
     * Send OTP for complaint verification
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

        $result = $this->otpService->generateAndSendOtp($request->barangay_id, 'complaint_verification');

        return response()->json($result);
    }

    /**
     * Verify OTP for complaint
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

        $result = $this->otpService->verifyOtp($request->barangay_id, $request->otp_code, 'complaint_verification');

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
