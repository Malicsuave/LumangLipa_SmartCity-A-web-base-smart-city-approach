<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use App\Models\Resident;
use App\Models\SeniorCitizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\SmsService;

class PreRegistrationController extends Controller
{
    /**
     * Show registration type choice page
     */
    public function chooseRegistrationType()
    {
        // Clear any previous registration data when starting fresh
        $this->clearRegistrationData();
        
        return view('public.register');
    }

    /**
     * Show Step 1: Personal Information
     */
    public function createStep1()
    {
        // Clear any previous registration data for a fresh start
        // Only clear if no current registration is in progress
        if (!Session::has('pre_registration.step1')) {
            $this->clearRegistrationData();
        }
        
        // Clear any previous validation errors for a fresh start
        $errors = session()->get('errors');
        if ($errors) {
            session()->forget('errors');
        }
        
        // Get saved step1 data from session (if exists)
        $step1 = Session::get('pre_registration.step1', []);
        
        return view('public.pre-registration.resident.step1', [
            'step1' => $step1
        ]);
    }
    
    /**
     * Clear all registration session data
     */
    private function clearRegistrationData()
    {
        Session::forget('pre_registration');
        Session::forget('temp_photo_preview');
        Session::forget('temp_signature_preview');
        Session::forget('temp_proof_preview');
    }

    /**
     * Store Step 1: Personal Information
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'type_of_resident' => 'required|in:Non-Migrant,Migrant,Transient',
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'suffix' => ['nullable', 'string', 'max:10', 'regex:/^[a-zA-Z\s\.]+$/'],
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'sex' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'citizenship_type' => 'required|in:FILIPINO,DUAL,NATURALIZED,FOREIGN',
            'citizenship_country' => 'nullable|required_if:citizenship_type,DUAL,FOREIGN|string|max:100',
            'educational_attainment' => 'required|string|max:100',
            'education_status' => 'required|in:Studying,Graduated,Stopped Schooling,Not Applicable',
            'religion' => 'nullable|string|max:100',
            'profession_occupation' => 'nullable|string|max:100',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'middle_name.regex' => 'Middle name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and dots.',
            'birthdate.before_or_equal' => 'Birthdate cannot be in the future.',
            'citizenship_country.required_if' => 'Please specify the country for dual citizenship or foreign national.',
        ]);

        Session::put('pre_registration.step1', $validated);
        return redirect()->route('public.pre-registration.step2');
    }

    /**
     * Show Step 2: Contact & Education Information
     */
    public function createStep2()
    {
        if (!Session::has('pre_registration.step1')) {
            return redirect()->route('public.pre-registration.step1');
        }
        
        // Get saved step2 data from session (if exists)
        $step2 = Session::get('pre_registration.step2', []);
        
        // Correct view path
        return view('public.pre-registration.resident.step2', [
            'step2' => $step2
        ]);
    }

    /**
     * Store Step 2: Contact & Address Information
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'contact_number' => 'required|numeric|digits:11',
            'email_address' => 'nullable|email|max:255',
            'address' => 'required|string',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|in:Parent,Spouse,Child,Sibling,Relative,Friend,Other',
            'emergency_contact_number' => 'required|numeric|digits:11',
            'emergency_contact_address' => 'required|string|max:500',
        ], [
            'contact_number.digits' => 'Contact number must be exactly 11 digits.',
            'emergency_contact_name.required' => 'Emergency contact person is required.',
            'emergency_contact_relationship.required' => 'Emergency contact relationship is required.',
            'emergency_contact_number.required' => 'Emergency contact number is required.',
            'emergency_contact_number.digits' => 'Emergency contact number must be exactly 11 digits.',
            'emergency_contact_address.required' => 'Emergency contact address is required.',
        ]);

        Session::put('pre_registration.step2', $validated);
        return redirect()->route('public.pre-registration.step3');
    }

    /**
     * Show Step 3: Household Information
     */
    public function createStep3()
    {
        if (!Session::has('pre_registration.step2')) {
            return redirect()->route('public.pre-registration.step2')
                ->with('error', 'Please complete step 2 first');
        }
        
        // Get saved step3 data from session (if exists)
        $step3 = Session::get('pre_registration.step3', []);
        
        return view('public.pre-registration.resident.step3', [
            'step3' => $step3
        ]);
    }

    /**
     * Store Step 3: Photo & Signature Upload (for resident registration)
     */
    public function storeStep3(Request $request)
    {
        try {
            // Check if we already have files in session
            $hasExistingPhoto = Session::has('pre_registration.step3.photo');
            $hasExistingProof = Session::has('pre_registration.step3.proof_of_residency');
            
            // Validate with conditional required for photo and proof
            $rules = [
                'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            ];
            
            // Only require photo if there isn't one already
            if (!$hasExistingPhoto) {
                $rules['photo'] = 'required|image|mimes:jpeg,jpg,png|max:5000';
            } else {
                $rules['photo'] = 'nullable|image|mimes:jpeg,jpg,png|max:5000';
            }
            
            // Only require proof of residency if there isn't one already
            if (!$hasExistingProof) {
                $rules['proof_of_residency'] = 'required|file|mimes:jpeg,jpg,png,pdf|max:5000';
            } else {
                $rules['proof_of_residency'] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5000';
            }
            
            $messages = [
                'photo.required' => 'Photo is required for ID generation.',
                'photo.max' => 'Photo file size must not exceed 5MB.',
                'signature.max' => 'Signature file size must not exceed 2MB.',
                'proof_of_residency.required' => 'Proof of residency document is required.',
                'proof_of_residency.max' => 'Proof of residency file size must not exceed 5MB.',
                'proof_of_residency.mimes' => 'Proof of residency must be a JPEG, PNG, or PDF file.',
            ];
            
            $validated = $request->validate($rules, $messages);

            // Get existing files data or initialize empty array
            $files = Session::get('pre_registration.step3', []);
            
            // Handle photo upload if provided
            if ($request->hasFile('photo')) {
                $photoFile = $request->file('photo');
                $files['photo'] = [
                    'name' => $photoFile->getClientOriginalName(),
                    'data' => base64_encode(file_get_contents($photoFile->getPathname())),
                    'mime' => $photoFile->getMimeType()
                ];
                
                // Store preview for display when navigating back
                Session::put('temp_photo_preview', 'data:' . $photoFile->getMimeType() . ';base64,' . base64_encode(file_get_contents($photoFile->getPathname())));
            }
            
            // Handle signature upload if provided
            if ($request->hasFile('signature')) {
                $signatureFile = $request->file('signature');
                $files['signature'] = [
                    'name' => $signatureFile->getClientOriginalName(),
                    'data' => base64_encode(file_get_contents($signatureFile->getPathname())),
                    'mime' => $signatureFile->getMimeType()
                ];
                
                // Store preview for display when navigating back
                Session::put('temp_signature_preview', 'data:' . $signatureFile->getMimeType() . ';base64,' . base64_encode(file_get_contents($signatureFile->getPathname())));
            }
            
            // Handle proof of residency upload if provided
            if ($request->hasFile('proof_of_residency')) {
                $proofFile = $request->file('proof_of_residency');
                $files['proof_of_residency'] = [
                    'name' => $proofFile->getClientOriginalName(),
                    'data' => base64_encode(file_get_contents($proofFile->getPathname())),
                    'mime' => $proofFile->getMimeType()
                ];
                
                // Store preview for display when navigating back (only for images, not PDFs)
                if (in_array($proofFile->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
                    Session::put('temp_proof_preview', 'data:' . $proofFile->getMimeType() . ';base64,' . base64_encode(file_get_contents($proofFile->getPathname())));
                } else {
                    // For PDFs, just store a flag
                    Session::put('temp_proof_preview', 'pdf_uploaded');
                }
            }
            
            Session::put('pre_registration.step3', $files);
            
            // Redirect to step 4 or review page
            return redirect()->route('public.pre-registration.review');
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Pre-registration step 3 error: ' . $e->getMessage());
            
            // Return with a more specific error message
            return redirect()->back()
                ->with('error', 'Error processing your upload: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show Step 4: Family Members
     */
    public function createStep4()
    {
        if (!Session::has('pre_registration.step3')) {
            return redirect()->route('public.pre-registration.step3')
                ->with('error', 'Please complete step 3 first');
        }
        return view('public.pre-registration.resident.step4');
    }

    /**
     * Store Step 4: Family Members (Optional)
     */
    public function storeStep4(Request $request)
    {
        // Family members are optional, only validate if provided
        $validated = $request->validate([
            'family_members' => 'nullable|array',
            'family_members.*.name' => 'required_with:family_members|string|max:255',
            'family_members.*.birthday' => 'required_with:family_members|date|before_or_equal:today',
            'family_members.*.gender' => 'required_with:family_members|in:Male,Female,Non-binary,Transgender,Other',
            'family_members.*.relationship' => 'required_with:family_members|string|max:100',
            'family_members.*.phone' => 'nullable|numeric|digits:11',
            'family_members.*.work' => 'nullable|string|max:100',
            'family_members.*.allergies' => 'nullable|string|max:255',
            'family_members.*.medical_condition' => 'nullable|string|max:255',
        ], [
            'family_members.*.name.required_with' => 'Family member name is required.',
            'family_members.*.name.max' => 'The family member name must not exceed 255 characters.',
            'family_members.*.birthday.required_with' => 'Family member birthday is required.',
            'family_members.*.birthday.date' => 'The birthday must be a valid date format.',
            'family_members.*.birthday.before_or_equal' => 'The birthday cannot be in the future.',
            'family_members.*.gender.required_with' => 'Family member gender is required.',
            'family_members.*.gender.in' => 'Please select a valid gender for the family member.',
            'family_members.*.relationship.required_with' => 'Family member relationship is required.',
            'family_members.*.phone.numeric' => 'The family member phone number must contain only numbers.',
            'family_members.*.phone.digits' => 'The family member phone number must be exactly 11 digits.',
        ]);

        // Store family members data (even if empty array)
        Session::put('pre_registration.step4', $validated);
        
        // Redirect to review page
        return redirect()->route('public.pre-registration.review');
    }

    /**
     * Show Review Page
     */
    public function createReview()
    {
        // Step 4 is optional, so we only require step 3
        if (!Session::has('pre_registration.step3')) {
            return redirect()->route('public.pre-registration.step3')
                ->with('error', 'Please complete step 3 first');
        }

        // Get all session data for review
        $step1 = Session::get('pre_registration.step1');
        $step2 = Session::get('pre_registration.step2');
        $step3 = Session::get('pre_registration.step3');
        $step4 = Session::get('pre_registration.step4', ['family_members' => []]); // Optional family members

        return view('public.pre-registration.resident.review', compact('step1', 'step2', 'step3', 'step4'));
    }

    /**
     * Final Submit - Store all data
     */
    public function store(Request $request)
    {
        Log::info('Pre-registration store method called', [
            'has_session_step1' => Session::has('pre_registration.step1'),
            'has_session_step2' => Session::has('pre_registration.step2'),
            'has_session_step3' => Session::has('pre_registration.step3'),
            'request_data' => $request->all(),
            'session_id' => session()->getId()
        ]);
        
        try {
            $request->validate([
                'final_confirmation' => 'required|accepted'
            ]);
            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        }

        Log::info('Checking session data existence');
        
        // Check if all required session data exists (step4 is optional)
        if (!Session::has('pre_registration.step1') || 
            !Session::has('pre_registration.step2') || 
            !Session::has('pre_registration.step3')) {
            Log::warning('Session data incomplete, redirecting to step 1');
            return redirect()->route('public.pre-registration.step1')
                ->with('error', 'Registration data is incomplete. Please start again.');
        }
        
        Log::info('Session data complete, proceeding to create registration');

        try {
            Log::info('Starting pre-registration processing');
            
            // Get all session data
            $step1 = Session::get('pre_registration.step1');
            $step2 = Session::get('pre_registration.step2');
            $step3 = Session::get('pre_registration.step3');
            
            Log::info('Session data retrieved', [
                'step1_keys' => array_keys($step1),
                'step2_keys' => array_keys($step2),
                'step3_keys' => array_keys($step3)
            ]);

            // Handle photo upload from step 3
            $photoFilename = null;
            if (isset($step3['photo'])) {
                Log::info('Processing photo');
                $photoFilename = $this->processPhoto($step3['photo']);
                Log::info('Photo processed', ['filename' => $photoFilename]);
            }

            // Handle optional signature upload from step 3
            $signatureFilename = null;
            if (isset($step3['signature'])) {
                Log::info('Processing signature');
                $signatureFilename = $this->processSignature($step3['signature']);
                Log::info('Signature processed', ['filename' => $signatureFilename]);
            }
            
            // Handle proof of residency upload from step 3
            $proofFilename = null;
            if (isset($step3['proof_of_residency'])) {
                Log::info('Processing proof of residency');
                $proofFilename = $this->processProofOfResidency($step3['proof_of_residency']);
                Log::info('Proof of residency processed', ['filename' => $proofFilename]);
            }
            
            Log::info('About to create pre-registration record');
            
            // Create pre-registration record
            $preRegistration = PreRegistration::create([
                // Step 1 - Personal Information
                'type_of_resident' => $step1['type_of_resident'],
                'first_name' => $step1['first_name'],
                'middle_name' => $step1['middle_name'] ?? null,
                'last_name' => $step1['last_name'],
                'suffix' => $step1['suffix'] ?? null,
                'birthplace' => $step1['birthplace'],
                'birthdate' => $step1['birthdate'],
                'sex' => $step1['sex'],
                'civil_status' => $step1['civil_status'],
                'citizenship_type' => $step1['citizenship_type'],
                'citizenship_country' => $step1['citizenship_country'] ?? null,
                'educational_attainment' => $step1['educational_attainment'],
                'education_status' => $step1['education_status'],
                'religion' => $step1['religion'] ?? null,
                'profession_occupation' => $step1['profession_occupation'] ?? null,
                
                // Step 2 - Contact & Address Information
                'contact_number' => $step2['contact_number'],
                'email_address' => $step2['email_address'] ?? null,
                'address' => $step2['address'],
                'emergency_contact_name' => $step2['emergency_contact_name'] ?? null,
                'emergency_contact_relationship' => $step2['emergency_contact_relationship'] ?? null,
                'emergency_contact_number' => $step2['emergency_contact_number'] ?? $step2['contact_number'],
                'emergency_contact_address' => $step2['emergency_contact_address'] ?? null,
                
                // Step 3 - Photos & Documents
                'photo' => $photoFilename,
                'signature' => $signatureFilename,
                'proof_of_residency' => $proofFilename,
                
                // Status
                'status' => 'pending',
            ]);
            
            Log::info('Pre-registration record created', ['id' => $preRegistration->id]);

            // Send SMS notification
            try {
                Log::info('Attempting to send SMS notification');
                $smsService = new SmsService();
                $fullName = trim($step1['first_name'] . ' ' . $step1['last_name']);
                $smsService->sendPreRegistrationConfirmation($step2['contact_number'], $fullName);
                Log::info('SMS notification sent successfully');
            } catch (\Exception $e) {
                Log::warning('SMS notification failed: ' . $e->getMessage());
                // Don't fail the registration if SMS fails
            }

            // Clear all registration session data
            Log::info('Clearing registration session data');
            $this->clearRegistrationData();

            Log::info('Redirecting to success page', [
                'registration_id' => $preRegistration->registration_id
            ]);

            return redirect()->route('public.pre-registration.success')
                ->with('success', 'Your registration has been submitted successfully! You will receive your ID via email once approved.')
                ->with('registration_email', $step2['email_address'] ?? null)
                ->with('registration_phone', $step2['contact_number'])
                ->with('registration_id', $preRegistration->registration_id);

        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Pre-registration submission error: ' . $e->getMessage());
            Log::error('Error file: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Error details: ' . $e->getTraceAsString());
            
            // If it's a PDO exception, log the SQL error code
            if ($e instanceof \PDOException) {
                Log::error('SQL Error Code: ' . $e->getCode());
                Log::error('SQL Error Info: ' . json_encode($e->errorInfo ?? []));
            }
            
            // Check if it's a database error
            if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                // Show detailed error in development
                if (config('app.env') === 'local' || config('app.debug')) {
                    return redirect()->back()
                        ->with('error', 'Database error: ' . $e->getMessage());
                } else {
                    return redirect()->back()
                        ->with('error', 'Database error: There was a problem saving your registration. This might be due to a duplicate email address or database connection issue.');
                }
            }
            
            // Check if it's a file system error
            if (strpos($e->getMessage(), 'storage') !== false || strpos($e->getMessage(), 'permission') !== false) {
                return redirect()->back()
                    ->with('error', 'File system error: There was a problem saving your uploaded files. Please try again or contact support.');
            }
            
            // Return with the actual error message for debugging in development
            if (config('app.env') === 'local' || config('app.debug')) {
                return redirect()->back()
                    ->with('error', 'Error: ' . $e->getMessage())
                    ->withInput();
            }
            
            // Generic error message for production
            return redirect()->back()
                ->with('error', 'There was an error processing your registration. Please try again or contact support.')
                ->withInput();
        }
    }

    /**
     * Show success page after registration.
     */
    public function success()
    {
        if (!session()->has('success')) {
            return redirect()->route('public.pre-registration.step1');
        }

        return view('public.pre-registration.success');
    }

    /**
     * Check registration status by email.
     */
    public function checkStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email'
            ]);

            $registration = PreRegistration::where('email_address', $request->email)->first();

            if (!$registration) {
                return redirect()->back()
                    ->with('error', 'No registration found with this email address.');
            }

            return view('public.pre-registration.status', compact('registration'));
        }

        return view('public.pre-registration.check-status');
    }

    /**
     * Process and save photo from base64 data.
     */
    private function processPhoto($fileData)
    {
        // Decode base64 data
        $imageData = base64_decode($fileData['data']);
        
        // Create image from string
        $image = Image::make($imageData);
        
        // Resize to standard ID photo size (2x2 inches at 300dpi = 600x600 pixels)
        $image->fit(600, 600);
        
        // Generate unique filename
        $filename = 'prereg_' . time() . '_' . Str::random(10) . '.jpg';
        
        // Save the processed image
        $path = storage_path('app/public/pre-registrations/photos/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // Save image with compression
        $image->save($path, 80);
        
        return $filename;
    }

    /**
     * Process and save signature from base64 data.
     */
    private function processSignature($fileData)
    {
        // Decode base64 data
        $imageData = base64_decode($fileData['data']);
        
        // Create image from string
        $image = Image::make($imageData);
        
        // Resize signature to standard size
        $image->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Generate unique filename
        $filename = 'prereg_sig_' . time() . '_' . Str::random(10) . '.png';
        
        // Save the processed image
        $path = storage_path('app/public/pre-registrations/signatures/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // Save image with compression
        $image->save($path, 80);
        
        return $filename;
    }

    /**
     * Process and save proof of residency document from base64 data.
     */
    private function processProofOfResidency($fileData)
    {
        // Decode base64 data
        $fileContent = base64_decode($fileData['data']);
        
        // Get the mime type
        $mimeType = $fileData['mime'];
        
        // Determine file extension
        $extension = 'jpg'; // default
        if ($mimeType === 'application/pdf') {
            $extension = 'pdf';
        } elseif ($mimeType === 'image/png') {
            $extension = 'png';
        } elseif ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
            $extension = 'jpg';
        }
        
        // Generate unique filename
        $filename = 'prereg_proof_' . time() . '_' . Str::random(10) . '.' . $extension;
        
        // Save path
        $path = storage_path('app/public/pre-registrations/proof-of-residency/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // For PDFs, just save the file directly
        if ($extension === 'pdf') {
            file_put_contents($path, $fileContent);
        } else {
            // For images, process and compress
            $image = Image::make($fileContent);
            
            // Resize if too large (max 1600px width while maintaining aspect ratio)
            $image->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Save image with compression
            $image->save($path, 85);
        }
        
        return $filename;
    }
}
