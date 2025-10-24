<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Resident;
use App\Models\HealthServiceRequest;
use App\Models\HealthAppointmentDate;
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
                'appointment_date_id' => 'required|exists:health_appointment_dates,id',
                'service_type' => 'nullable|string',
                'purpose' => 'nullable|string',
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

            // Check if appointment date is still available
            $appointmentDate = HealthAppointmentDate::findOrFail($request->appointment_date_id);
            
            if ($appointmentDate->status !== 'open') {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment date is no longer available.'
                ], 400);
            }

            if ($appointmentDate->is_full) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment date is fully booked.'
                ], 400);
            }

            // Create health service request
            $healthService = HealthServiceRequest::create([
                'barangay_id' => $request->barangay_id,
                'appointment_date_id' => $request->appointment_date_id,
                'service_type' => $request->service_type ?? 'General Health Check-up',
                'purpose' => $request->purpose ?? 'Scheduled health check-up appointment',
                'status' => 'pending',
                'requested_at' => now(),
                'scheduled_at' => $appointmentDate->appointment_date
            ]);

            // Increment booked slots
            $appointmentDate->increment('booked_slots');

            return response()->json([
                'success' => true,
                'message' => 'Health appointment booked successfully!',
                'data' => [
                    'request_id' => $healthService->id,
                    'resident_name' => $resident->first_name . ' ' . $resident->last_name,
                    'appointment_date' => $appointmentDate->appointment_date->format('F d, Y'),
                    'appointment_time' => $appointmentDate->start_time . ' - ' . $appointmentDate->end_time,
                    'location' => $appointmentDate->location,
                    'status' => $healthService->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Health Service - Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to book appointment. Please try again.'
            ], 500);
        }
    }

    /**
     * Get available appointment dates for residents to book
     */
    public function getAvailableAppointmentDates()
    {
        try {
            $dates = HealthAppointmentDate::where('status', 'open')
                ->where('appointment_date', '>=', now()->toDateString())
                ->orderBy('appointment_date', 'asc')
                ->get()
                ->map(function($date) {
                    return [
                        'id' => $date->id,
                        'title' => $date->title,
                        'date' => $date->appointment_date->format('Y-m-d'),
                        'date_formatted' => $date->appointment_date->format('F d, Y'),
                        'time' => $date->start_time . ' - ' . $date->end_time,
                        'location' => $date->location,
                        'available_slots' => $date->available_slots,
                        'max_slots' => $date->max_slots,
                        'is_full' => $date->is_full,
                        'description' => $date->description
                    ];
                });

            return response()->json([
                'success' => true,
                'dates' => $dates
            ]);
        } catch (\Exception $e) {
            Log::error('Get Available Appointment Dates Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch available dates.'
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

    /**
     * Admin: View all appointment dates
     */
    public function appointmentDatesIndex()
    {
        try {
            $appointmentDates = HealthAppointmentDate::with('creator')
                ->orderBy('appointment_date', 'desc')
                ->paginate(15);

            return view('admin.health.appointment-dates.index', compact('appointmentDates'));
        } catch (\Exception $e) {
            Log::error('Appointment Dates Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading appointment dates.');
        }
    }

    /**
     * Admin: Create appointment date form
     */
    public function createAppointmentDate()
    {
        return view('admin.health.appointment-dates.create');
    }

    /**
     * Admin: Store new appointment date
     */
    public function storeAppointmentDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'appointment_date' => 'required|date|after_or_equal:today',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'start_time' => 'required',
                'end_time' => 'required',
                'max_slots' => 'required|integer|min:1|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            HealthAppointmentDate::create([
                'appointment_date' => $request->appointment_date,
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'max_slots' => $request->max_slots,
                'booked_slots' => 0,
                'status' => 'open',
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.health.appointment-dates.index')
                ->with('success', 'Appointment date created successfully!');

        } catch (\Exception $e) {
            Log::error('Store Appointment Date Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create appointment date.')
                ->withInput();
        }
    }

    /**
     * Admin: View appointments for a specific date
     */
    public function viewAppointmentsByDate($id)
    {
        try {
            $appointmentDate = HealthAppointmentDate::with(['appointments.resident'])->findOrFail($id);
            
            return view('admin.health.appointment-dates.view', compact('appointmentDate'));
        } catch (\Exception $e) {
            Log::error('View Appointments By Date Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading appointments.');
        }
    }

    /**
     * Admin: Update appointment date status
     */
    public function updateAppointmentDateStatus($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:open,closed,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointmentDate = HealthAppointmentDate::findOrFail($id);
            $appointmentDate->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment date status updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Appointment Date Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating appointment date status.'
            ], 500);
        }
    }
}

