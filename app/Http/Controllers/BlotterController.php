<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blotter;
use App\Models\Resident;
use App\Services\OtpService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BlotterController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show the form for creating a new blotter report.
     */
    public function create()
    {
        return view('public.forms.blotter-request');
    }

    /**
     * Check if resident exists and return resident information.
     */
    public function checkResident(Request $request)
    {
        try {
            $barangayId = $request->barangay_id;

            if (empty($barangayId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barangay ID is required'
                ]);
            }

            $resident = Resident::where('barangay_id', $barangayId)->first();

            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found. Please check your Barangay ID or contact the barangay office for assistance.'
                ]);
            }

            return response()->json([
                'success' => true,
                'resident' => [
                    'name' => $resident->first_name . ' ' . $resident->middle_name . ' ' . $resident->last_name,
                    'address' => $resident->address,
                    'age' => $resident->age,
                    'contact_number' => $resident->contact_number
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BlotterController@checkResident: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking resident information.'
            ]);
        }
    }

    /**
     * Send OTP to resident's email.
     */
    public function sendOtp(Request $request)
    {
        try {
            $barangayId = $request->barangay_id;
            
            if (empty($barangayId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barangay ID is required'
                ]);
            }

            // Use the same method as ComplaintController
            $result = $this->otpService->generateAndSendOtp($barangayId, 'blotter_report');

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error in BlotterController@sendOtp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending OTP.'
            ]);
        }
    }    /**
     * Verify the OTP code.
     */
    public function verifyOtp(Request $request)
    {
        try {
            $barangayId = $request->barangay_id;
            $otpCode = $request->otp_code;

            if (empty($barangayId) || empty($otpCode)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barangay ID and OTP code are required'
                ]);
            }

            // Use the same method as ComplaintController
            $result = $this->otpService->verifyOtp($barangayId, $otpCode, 'blotter_report');

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error in BlotterController@verifyOtp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying OTP.'
            ]);
        }
    }    /**
     * Store a newly created blotter report.
     */
    public function store(Request $request)
    {
        try {
            // Verify resident exists first
            $resident = Resident::where('barangay_id', $request->barangay_id)->first();
            
            if (!$resident) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident not found'
                ]);
            }

            // Check if resident is a minor
            if ($resident->age < 18) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be 18 years old or above to file a blotter report online. Minors are not allowed to file blotter reports through this system. Please go to the barangay office with your guardian to file a blotter report.'
                ]);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'barangay_id' => 'required|string',
                'incident_type' => 'required|string',
                'incident_title' => 'required|string|max:255',
                'incident_description' => 'required|string',
                'incident_date' => 'required|date',
                'incident_time' => 'nullable|string',
                'incident_location' => 'required|string',
                'parties_involved' => 'required|string',
                'witnesses' => 'nullable|string',
                'desired_resolution' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate unique blotter ID
            $blotterId = 'BLT-' . date('Y-m-d') . '-' . str_pad(Blotter::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create the blotter report
            $blotter = Blotter::create([
                'blotter_id' => $blotterId,
                'barangay_id' => $request->barangay_id,
                'resident_id' => $resident->id,
                'incident_type' => $request->incident_type,
                'incident_title' => $request->incident_title,
                'incident_description' => $request->incident_description,
                'incident_date' => $request->incident_date,
                'incident_time' => $request->incident_time,
                'incident_location' => $request->incident_location,
                'parties_involved' => $request->parties_involved,
                'witnesses' => $request->witnesses,
                'desired_resolution' => $request->desired_resolution,
                'status' => 'pending',
                'filed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Blotter report submitted successfully! Your report will be reviewed by barangay officials.',
                'data' => [
                    'blotter_id' => $blotterId,
                    'status' => 'pending'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in BlotterController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your blotter report. Please try again.'
            ], 500);
        }
    }

    /**
     * Mask email address for privacy.
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        
        // Show first 2 characters and last 1 character of the name part
        $maskedName = substr($name, 0, 2) . str_repeat('*', strlen($name) - 3) . substr($name, -1);
        
        return $maskedName . '@' . $domain;
    }
}