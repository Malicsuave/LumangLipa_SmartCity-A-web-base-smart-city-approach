<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Resident;
use App\Models\HealthServiceRequest;
use App\Services\OtpService;
use Carbon\Carbon;
use Zxing\QrReader;

class HealthServiceController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
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
        }

        $age = $resident->birthdate ? Carbon::parse($resident->birthdate)->age : null;

        return response()->json([
            'success' => true,
            'resident' => [
                'name' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
                'address' => $resident->address,
                'age' => $age ?? 'N/A',
                'contact' => $resident->contact_number,
                'email' => $resident->email
            ]
        ]);
    }

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
                'contact' => $resident->contact_number,
                'email' => $resident->email
            ];
        }

        return response()->json($result);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'barangay_id' => 'required|string',
                'service_type' => 'required|string',
                'appointment_type' => 'required|string|in:walk-in,scheduled',
                'health_concern' => 'required|string',
                'priority' => 'required|string|in:low,medium,high,emergency',
                'symptoms' => 'nullable|string',
                'preferred_date' => 'nullable|date|after:today',
                'preferred_time' => 'nullable|string',
                'verification_method' => 'required|string|in:manual,qr'
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
                    'message' => 'Resident not found.'
                ], 404);
            }

            // Create health service request using the existing table structure
            $healthService = HealthServiceRequest::create([
                'barangay_id' => $request->barangay_id,
                'service_type' => $request->service_type,
                'priority' => $request->priority,
                'purpose' => $request->health_concern, // Map health_concern to purpose field in DB
                'health_concern' => $request->health_concern,
                'symptoms' => $request->symptoms,
                'status' => 'pending',
                'requested_at' => now(),
                'scheduled_at' => $request->appointment_type === 'scheduled' && $request->preferred_date && $request->preferred_time 
                    ? $request->preferred_date . ' ' . $request->preferred_time 
                    : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Health service request submitted successfully!',
                'data' => [
                    'request_id' => $healthService->id,
                    'resident_name' => $resident->first_name . ' ' . $resident->last_name,
                    'service_type' => $healthService->service_type,
                    'appointment_type' => $request->appointment_type,
                    'status' => $healthService->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Health Service - Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit health service request. Please try again.'
            ], 500);
        }
    }

    public function adminDashboard()
    {
        try {
            $healthServices = HealthServiceRequest::with('resident')
                ->orderBy('created_at', 'desc')
                ->get();

            $stats = [
                'total' => HealthServiceRequest::count(),
                'pending' => HealthServiceRequest::where('status', 'pending')->count(),
                'approved' => HealthServiceRequest::where('status', 'approved')->count(),
                'completed' => HealthServiceRequest::where('status', 'completed')->count(),
                'rejected' => HealthServiceRequest::where('status', 'rejected')->count()
            ];

            return view('admin.health', compact('healthServices', 'stats'));
        } catch (\Exception $e) {
            Log::error('Health Service - Admin Dashboard Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading health services dashboard.');
        }
    }

    public function index()
    {
        try {
            $healthServices = HealthServiceRequest::with('resident')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('admin.health.index', compact('healthServices'));
        } catch (\Exception $e) {
            Log::error('Health Service - Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading health services.');
        }
    }

    public function show($id)
    {
        try {
            $healthService = HealthServiceRequest::with('resident')->findOrFail($id);
            return view('admin.health.show', compact('healthService'));
        } catch (\Exception $e) {
            Log::error('Health Service - Show Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Health service not found.');
        }
    }

    public function approve($id)
    {
        try {
            $healthService = HealthServiceRequest::findOrFail($id);
            $healthService->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Health service request approved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Health Service - Approve Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error approving health service request.'
            ], 500);
        }
    }

    public function complete($id)
    {
        try {
            $healthService = HealthServiceRequest::findOrFail($id);
            $healthService->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Health service marked as completed.'
            ]);
        } catch (\Exception $e) {
            Log::error('Health Service - Complete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error completing health service.'
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $healthService = HealthServiceRequest::findOrFail($id);
            $healthService->update(['status' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Health service request rejected.'
            ]);
        } catch (\Exception $e) {
            Log::error('Health Service - Reject Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting health service request.'
            ], 500);
        }
    }

    public function decodeQr(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image file. Please upload a valid image (JPEG, PNG, JPG, GIF) under 10MB.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $image = $request->file('qr_image');
            
            // Log for debugging
            Log::info('Health QR Code decode attempt', [
                'file_name' => $image->getClientOriginalName(),
                'file_size' => $image->getSize(),
                'mime_type' => $image->getMimeType()
            ]);
            
            $qrData = $this->decodeQrFromImage($image);
            
            if ($qrData) {
                // Log successful decode
                Log::info('Health QR Code decoded successfully', [
                    'qr_data' => $qrData,
                    'data_length' => strlen($qrData)
                ]);
                
                return response()->json([
                    'success' => true,
                    'qr_data' => $qrData,
                    'message' => 'QR code decoded successfully',
                    'debug_info' => [
                        'data_length' => strlen($qrData),
                        'data_preview' => substr($qrData, 0, 50) . (strlen($qrData) > 50 ? '...' : '')
                    ]
                ]);
            } else {
                Log::warning('Health QR Code decode failed - no data extracted');
                
                return response()->json([
                    'success' => false,
                    'message' => 'Could not decode QR code from the image. Please ensure the image contains a clear, valid QR code.'
                ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Health QR Code decode error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the QR code. Please try again.',
                'error_details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Decode QR code from image using external service or library
     */
    private function decodeQrFromImage($imageFile)
    {
        try {
            // Store the uploaded file temporarily
            $tempPath = $imageFile->store('temp', 'local');
            $fullPath = storage_path('app/' . $tempPath);
            
            Log::info('Health QR decode attempt', [
                'temp_path' => $tempPath,
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath)
            ]);
            
            // Method 1: Use the PHP QR code library
            $qrcode = new QrReader($fullPath);
            $qrData = $qrcode->text();
            
            Log::info('Health QR Reader result', [
                'raw_result' => $qrData,
                'result_type' => gettype($qrData),
                'is_empty' => empty($qrData),
                'length' => $qrData ? strlen($qrData) : 0
            ]);
            
            // Clean up temp file
            Storage::disk('local')->delete($tempPath);
            
            if ($qrData && !empty(trim($qrData))) {
                $cleanData = trim($qrData);
                Log::info('Health QR decode success', ['clean_data' => $cleanData]);
                return $cleanData;
            }
            
            // If the library didn't work, log and return null
            Log::warning('Health QR code could not be decoded from image - empty result');
            return null;
            
        } catch (\Exception $e) {
            Log::error('Health QR decode error: ' . $e->getMessage(), [
                'error_type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Clean up temp file if it exists
            if (isset($tempPath)) {
                try {
                    Storage::disk('local')->delete($tempPath);
                } catch (\Exception $cleanupError) {
                    Log::error('Failed to cleanup temp file: ' . $cleanupError->getMessage());
                }
            }
            
            return null;
        }
    }
}
