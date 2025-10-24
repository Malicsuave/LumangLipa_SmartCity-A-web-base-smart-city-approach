<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlotterComplaint;
use App\Models\Resident;
use App\Services\OtpService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Zxing\QrReader;

class BlotterComplaintController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function create()
    {
        return view('public.forms.blotter-complaint-request');
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
                'message' => 'Resident not found. Please check your Barangay ID.'
            ], 404);
        }

        $age = $resident->date_of_birth ? Carbon::parse($resident->date_of_birth)->age : 'N/A';

        return response()->json([
            'success' => true,
            'resident' => [
                'name' => trim($resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name),
                'address' => trim(($resident->house_number ?? '') . ' ' . ($resident->street ?? '') . ', ' . ($resident->zone ?? '')),
                'age' => $age,
                'contact_number' => $resident->mobile_number ?? $resident->telephone_number ?? 'N/A',
                'email' => $resident->email ?? 'N/A',
            ],
            'qr_verified' => $request->qr_verified ?? false
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

        try {
            $result = $this->otpService->generateAndSendOtp($request->barangay_id, 'blotter_verification');
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'email_hint' => $result['email_hint'],
                    'expires_at' => $result['expires_at']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to send OTP.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('OTP Email Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
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

        $result = $this->otpService->verifyOtp($request->barangay_id, $request->otp_code, 'blotter_verification');

        return response()->json($result);
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
            Log::info('Blotter QR Code decode attempt', [
                'file_name' => $image->getClientOriginalName(),
                'file_size' => $image->getSize(),
                'mime_type' => $image->getMimeType()
            ]);
            
            $qrData = $this->decodeQrFromImage($image);
            
            if ($qrData) {
                // Log successful decode
                Log::info('Blotter QR Code decoded successfully', [
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
                Log::warning('Blotter QR Code decode failed - no data extracted');
                
                return response()->json([
                    'success' => false,
                    'message' => 'Could not decode QR code from the image. Please ensure the image contains a clear, valid QR code.'
                ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Blotter QR Code decode error: ' . $e->getMessage(), [
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
        $tempPath = null;
        try {
            // Store the uploaded file temporarily
            $tempPath = $imageFile->store('temp', 'local');
            
            // Check if file was stored successfully
            if (!$tempPath) {
                Log::error('Blotter QR decode error: Failed to store temporary file.');
                return null;
            }

            $fullPath = storage_path('app/' . $tempPath);
            
            Log::info('Blotter QR decode attempt', [
                'temp_path' => $tempPath,
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath)
            ]);
            
            $qrData = null;
            try {
                // Use the PHP QR code library
                $qrcode = new QrReader($fullPath);
                $qrData = $qrcode->text();
            } catch (\Exception $e) {
                Log::error('QrReader library error: ' . $e->getMessage());
            }

            Log::info('Blotter QR Reader result', [
                'raw_result' => $qrData,
                'result_type' => gettype($qrData),
                'is_empty' => empty($qrData),
                'length' => $qrData ? strlen($qrData) : 0
            ]);
            
            // Clean up temp file
            Storage::disk('local')->delete($tempPath);
            
            if ($qrData && !empty(trim($qrData))) {
                $cleanData = trim($qrData);
                Log::info('Blotter QR decode success', ['clean_data' => $cleanData]);
                return $cleanData;
            }
            
            // If the library didn't work, log and return null
            Log::warning('Blotter QR code could not be decoded from image - empty result');
            return null;
            
        } catch (\Exception $e) {
            Log::error('Blotter QR decode error: ' . $e->getMessage(), [
                'error_type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Clean up temp file if it exists
            if (isset($tempPath) && $tempPath) {
                try {
                    Storage::disk('local')->delete($tempPath);
                } catch (\Exception $cleanupError) {
                    Log::error('Failed to cleanup temp file: ' . $cleanupError->getMessage());
                }
            }
            
            return null;
        }
    }

    public function store(Request $request)
    {
        $isQrVerification = $request->input('verification_method') === 'qr';

        $validationRules = [
            'barangay_id' => 'required|string|exists:residents,barangay_id',
            'complainants' => 'required|string',
            'respondents' => 'required|string',
            'complaint_details' => 'required|string',
            'resolution_sought' => 'required|string',
            'verification_method' => 'required|string|in:manual,qr',
        ];

        $validator = Validator::make($request->all(), $validationRules);

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
                'message' => 'Resident not found with the provided Barangay ID.'
            ], 404);
        }

        if (!$isQrVerification && !$this->otpService->hasValidOtp($request->barangay_id, 'blotter_verification')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your identity with the OTP sent to your email before submitting.'
            ], 422);
        }

        try {
            $caseNumber = BlotterComplaint::generateCaseNumber();

            $complaint = BlotterComplaint::create([
                'barangay_id' => $request->barangay_id,
                'case_number' => $caseNumber,
                'complainants' => $request->complainants,
                'respondents' => $request->respondents,
                'complaint_details' => $request->complaint_details,
                'resolution_sought' => $request->resolution_sought,
                'verification_method' => $request->verification_method,
                'status' => 'pending',
            ]);

            if ($resident && $resident->email) {
                try {
                    Mail::send('emails.blotter-confirmation', [
                        'name' => $resident->first_name,
                        'case_number' => $caseNumber,
                        'complaint_id' => $complaint->id,
                        'created_at' => $complaint->created_at->format('F d, Y h:i A')
                    ], function ($message) use ($resident) {
                        $message->to($resident->email)
                            ->subject('Blotter/Complaint Report Submitted - Barangay Lumanglipa');
                    });
                } catch (\Exception $e) {
                    Log::error('Confirmation Email Error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Blotter/Complaint submitted successfully!',
                'complaint_id' => $complaint->id,
                'case_number' => $caseNumber
            ]);

        } catch (\Exception $e) {
            Log::error('Blotter Store Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your report. Please try again.'
            ], 500);
        }
    }
}
