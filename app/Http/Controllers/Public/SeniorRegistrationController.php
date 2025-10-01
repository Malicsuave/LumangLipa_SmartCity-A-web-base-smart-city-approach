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

class SeniorRegistrationController extends Controller
{
    /**
     * Show Step 1: Personal Information (Senior Citizen)
     */
    public function createStep1()
    {
        return view('public.senior-registration.step1');
    }

    /**
     * Store Step 1: Personal Information (Senior Citizen)
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'suffix' => ['nullable', 'string', 'max:10', 'regex:/^[a-zA-Z\s\.]+$/'],
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'sex' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
            'type_of_resident' => 'required|in:Migrant,Non-Migrant,Transient',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'middle_name.regex' => 'Middle name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and dots.',
            'birthdate.before_or_equal' => 'Birthdate cannot be in the future.',
        ]);

        // Verify the person is actually a senior citizen (60+ years old)
        $age = Carbon::parse($validated['birthdate'])->age;
        if ($age < 60) {
            return redirect()->back()
                ->with('error', 'Senior citizen registration is only available for individuals aged 60 and above. Please use the regular resident registration instead.')
                ->withInput();
        }

        Session::put('senior_registration.step1', $validated);
        return redirect()->route('public.senior-registration.step2');
    }

    /**
     * Show Step 2: Contact & Education Information
     */
    public function createStep2()
    {
        if (!Session::has('senior_registration.step1')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Please complete step 1 first');
        }

        return view('public.senior-registration.step2');
    }

    /**
     * Store Step 2: Contact & Education Information
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'citizenship_type' => 'required|string|in:FILIPINO,Dual Citizen,Foreigner',
            'citizenship_country' => 'nullable|required_if:citizenship_type,Dual Citizen,Foreigner|string|max:100',
            'profession_occupation' => 'nullable|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            'contact_number' => 'required|numeric|digits:11',
            'email_address' => [
                'required',
                'email',
                'max:255',
                Rule::unique('pre_registrations', 'email_address'),
                Rule::unique('residents', 'email_address'),
                Rule::unique('senior_citizens', 'email_address')
            ],
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|string|max:100',
            'education_status' => 'nullable|in:Studying,Graduated,Stopped Schooling,Not Applicable',
            'address' => 'required|string|max:255',
        ], [
            'contact_number.digits' => 'Contact number must be exactly 11 digits.',
            'email_address.unique' => 'This email address is already registered or pending registration.',
        ]);

        Session::put('senior_registration.step2', $validated);
        return redirect()->route('public.senior-registration.step3');
    }

    /**
     * Show Step 3: Senior Citizen Specific Information
     */
    public function createStep3()
    {
        if (!Session::has('senior_registration.step2')) {
            return redirect()->route('public.senior-registration.step2')
                ->with('error', 'Please complete step 2 first');
        }

        return view('public.senior-registration.step3');
    }

    /**
     * Store Step 3: Senior Citizen Specific Information
     */
    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'pension_type' => 'nullable|string|max:100',
            'pension_amount' => 'nullable|numeric|min:0',
            'health_conditions' => 'nullable|string|max:500',
            'medications' => 'nullable|string|max:500',
            'living_arrangement' => 'nullable|in:Alone,With Family,With Caregiver,Assisted Living',
            'mobility_status' => 'nullable|in:Independent,Needs Assistance,Wheelchair Bound,Bedridden',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|numeric|digits:11',
            'philsys_id' => 'nullable|string|max:20|unique:pre_registrations,philsys_id|unique:residents,philsys_id|unique:senior_citizens,philsys_id',
            'mother_first_name' => 'nullable|string|max:100',
            'mother_middle_name' => 'nullable|string|max:100',
            'mother_last_name' => 'nullable|string|max:100',
        ], [
            'emergency_contact_number.digits' => 'Emergency contact number must be exactly 11 digits.',
            'philsys_id.unique' => 'This PhilSys ID is already registered.',
        ]);

        Session::put('senior_registration.step3', $validated);
        return redirect()->route('public.senior-registration.step4');
    }

    /**
     * Show Step 4: Household Information
     */
    public function createStep4()
    {
        if (!Session::has('senior_registration.step3')) {
            return redirect()->route('public.senior-registration.step3')
                ->with('error', 'Please complete step 3 first');
        }

        return view('public.senior-registration.step4');
    }

    /**
     * Store Step 4: Household Information
     */
    public function storeStep4(Request $request)
    {
        $validated = $request->validate([
            'primary_name' => 'required|string|max:255',
            'primary_birthday' => 'required|date|before_or_equal:today',
            'primary_gender' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'primary_phone' => 'required|numeric|digits:11',
            'primary_work' => 'nullable|string|max:100',
            'primary_allergies' => 'nullable|string|max:255',
            'primary_medical_condition' => 'nullable|string|max:255',
            'secondary_name' => 'nullable|string|max:255',
            'secondary_birthday' => 'nullable|date|before_or_equal:today',
            'secondary_gender' => 'nullable|in:Male,Female,Non-binary,Transgender,Other',
            'secondary_phone' => 'nullable|numeric|digits:11',
            'secondary_work' => 'nullable|string|max:100',
            'secondary_allergies' => 'nullable|string|max:255',
            'secondary_medical_condition' => 'nullable|string|max:255',
        ], [
            'primary_name.required' => "The primary person's name is required.",
            'primary_birthday.required' => "The primary person's birthday is required.",
            'primary_birthday.date' => 'The birthday must be a valid date format.',
            'primary_birthday.before_or_equal' => 'The birthday cannot be in the future.',
            'primary_gender.required' => "Please select the primary person's gender.",
            'primary_gender.in' => 'Please select a valid gender.',
            'primary_phone.required' => 'The primary phone number is required.',
            'primary_phone.numeric' => 'The phone number must contain only numbers.',
            'primary_phone.digits' => 'The primary phone number must be exactly 11 digits.',
            'secondary_phone.numeric' => 'The phone number must contain only numbers.',
            'secondary_phone.digits' => 'The secondary phone number must be exactly 11 digits.',
            'secondary_birthday.date' => 'The birthday must be a valid date format.',
            'secondary_birthday.before_or_equal' => 'The birthday cannot be in the future.',
            'secondary_gender.in' => 'Please select a valid gender.',
        ]);

        Session::put('senior_registration.step4', $validated);
        return redirect()->route('public.senior-registration.step5');
    }

    /**
     * Show Step 5: Family Members
     */
    public function createStep5()
    {
        if (!Session::has('senior_registration.step4')) {
            return redirect()->route('public.senior-registration.step4')
                ->with('error', 'Please complete step 4 first');
        }

        return view('public.senior-registration.step5');
    }

    /**
     * Store Step 5: Family Members
     */
    public function storeStep5(Request $request)
    {
        $validated = $request->validate([
            'family_members.*.name' => 'nullable|string|max:255',
            'family_members.*.birthday' => 'nullable|date|before_or_equal:today',
            'family_members.*.gender' => 'nullable|in:Male,Female,Non-binary,Transgender,Other',
            'family_members.*.relationship' => 'nullable|string|max:100',
            'family_members.*.related_to' => 'nullable|in:primary,secondary,both',
            'family_members.*.phone' => 'nullable|numeric|digits:11',
            'family_members.*.work' => 'nullable|string|max:100',
            'family_members.*.allergies' => 'nullable|string|max:255',
            'family_members.*.medical_condition' => 'nullable|string|max:255',
        ], [
            'family_members.*.name.max' => 'The family member name must not exceed 255 characters.',
            'family_members.*.phone.numeric' => 'The family member phone number must contain only numbers.',
            'family_members.*.phone.digits' => 'The family member phone number must be exactly 11 digits.',
            'family_members.*.related_to.in' => 'The family member relation must be either primary, secondary, or both.',
            'family_members.*.birthday.date' => 'The birthday must be a valid date format.',
            'family_members.*.birthday.before_or_equal' => 'The birthday cannot be in the future.',
            'family_members.*.gender.in' => 'Please select a valid gender for the family member.',
        ]);

        Session::put('senior_registration.step5', $validated);
        return redirect()->route('public.senior-registration.step6');
    }

    /**
     * Show Step 6: Photo & Signature Upload
     */
    public function createStep6()
    {
        if (!Session::has('senior_registration.step5')) {
            return redirect()->route('public.senior-registration.step5')
                ->with('error', 'Please complete step 5 first');
        }

        // Get previously uploaded photo/signature data if available
        $photoData = null;
        $signatureData = null;

        if (Session::has('senior_registration.step6')) {
            $step6Data = Session::get('senior_registration.step6');

            if (isset($step6Data['photo'])) {
                $photoData = 'data:' . $step6Data['photo']['mime'] . ';base64,' . $step6Data['photo']['data'];
            }

            if (isset($step6Data['signature'])) {
                $signatureData = 'data:' . $step6Data['signature']['mime'] . ';base64,' . $step6Data['signature']['data'];
            }
        }

        return view('public.senior-registration.step6', compact('photoData', 'signatureData'));
    }

    /**
     * Store Step 6: Photo & Signature Upload
     */
    public function storeStep6(Request $request)
    {
        try {
            // Check if we already have photos in session
            $hasExistingPhoto = Session::has('senior_registration.step6.photo');

            // Validate with conditional required for photo
            $rules = [
                'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
                'terms_accepted' => 'required|accepted',
            ];

            // Only require photo if there isn't one already
            if (!$hasExistingPhoto) {
                $rules['photo'] = 'required|image|mimes:jpeg,jpg,png|max:5000';
            } else {
                $rules['photo'] = 'nullable|image|mimes:jpeg,jpg,png|max:5000';
            }

            $messages = [
                'photo.required' => 'Photo is required for Senior ID generation.',
                'photo.max' => 'Photo file size must not exceed 5MB.',
                'signature.max' => 'Signature file size must not exceed 2MB.',
                'terms_accepted.required' => 'You must accept the terms and conditions to proceed.',
            ];

            $validated = $request->validate($rules, $messages);

            // Get existing files data or initialize empty array
            $files = Session::get('senior_registration.step6', []);

            // Handle photo upload if provided
            if ($request->hasFile('photo')) {
                $photoFile = $request->file('photo');
                $files['photo'] = [
                    'name' => $photoFile->getClientOriginalName(),
                    'data' => base64_encode(file_get_contents($photoFile->getPathname())),
                    'mime' => $photoFile->getMimeType()
                ];
            }

            // Handle signature upload if provided
            if ($request->hasFile('signature')) {
                $signatureFile = $request->file('signature');
                $files['signature'] = [
                    'name' => $signatureFile->getClientOriginalName(),
                    'data' => base64_encode(file_get_contents($signatureFile->getPathname())),
                    'mime' => $signatureFile->getMimeType()
                ];
            }

            $files['terms_accepted'] = $validated['terms_accepted'];

            Session::put('senior_registration.step6', $files);
            return redirect()->route('public.senior-registration.review');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Senior registration step 6 error: ' . $e->getMessage());

            // Return with a more specific error message
            return redirect()->back()
                ->with('error', 'Error processing your upload: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show Review Page
     */
    public function createReview()
    {
        if (!Session::has('senior_registration.step5')) {
            return redirect()->route('public.senior-registration.step5')
                ->with('error', 'Please complete step 5 first');
        }

        return view('public.senior-registration.review');
    }

    /**
     * Final Submit - Store all senior citizen data
     */
    public function store(Request $request)
    {
        $request->validate([
            'final_confirmation' => 'required|accepted'
        ]);

        // Check if all session data exists
        if (!Session::has('senior_registration.step1') ||
            !Session::has('senior_registration.step2') ||
            !Session::has('senior_registration.step3') ||
            !Session::has('senior_registration.step4') ||
            !Session::has('senior_registration.step5')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Registration data is incomplete. Please start again.');
        }

        // Get email address from session
        $emailAddress = Session::get('senior_registration.step2.email_address');

        // Check if email is still unique (might have been taken since step 2 was completed)
        $existingPreReg = PreRegistration::where('email_address', $emailAddress)->first();
        $existingResident = Resident::where('email_address', $emailAddress)->first();
        $existingSenior = SeniorCitizen::where('email_address', $emailAddress)->first();

        if ($existingPreReg || $existingResident || $existingSenior) {
            return redirect()->back()
                ->with('error', 'This email address is already registered or pending registration. Please go back to step 2 and use a different email address.');
        }

        try {
            // Handle photo upload
            $photoFilename = null;
            $photoFile = Session::get('senior_registration.step6.photo');
            if ($photoFile) {
                $photoFilename = $this->processPhoto($photoFile);
            }

            // Handle optional signature upload
            $signatureFilename = null;
            $signatureFile = Session::get('senior_registration.step6.signature');
            if ($signatureFile) {
                $signatureFilename = $this->processSignature($signatureFile);
            }

            // Get all session data
            $step1 = Session::get('senior_registration.step1');
            $step2 = Session::get('senior_registration.step2');
            $step3 = Session::get('senior_registration.step3');
            $step4 = Session::get('senior_registration.step4');
            $step5 = Session::get('senior_registration.step5');

            // Prepare senior citizen specific data
            $seniorInfo = [
                'pension_type' => $step3['pension_type'] ?? null,
                'pension_amount' => $step3['pension_amount'] ?? null,
                'health_conditions' => $step3['health_conditions'] ?? null,
                'medications' => $step3['medications'] ?? null,
                'living_arrangement' => $step3['living_arrangement'] ?? null,
                'mobility_status' => $step3['mobility_status'] ?? null,
                'emergency_contact_name' => $step3['emergency_contact_name'] ?? null,
                'emergency_contact_relationship' => $step3['emergency_contact_relationship'] ?? null,
                'emergency_contact_number' => $step3['emergency_contact_number'] ?? null,
            ];

            // Prepare household data
            $householdData = [
                'primary_name' => $step4['primary_name'],
                'primary_birthday' => $step4['primary_birthday'],
                'primary_gender' => $step4['primary_gender'],
                'primary_phone' => $step4['primary_phone'],
                'primary_work' => $step4['primary_work'] ?? null,
                'primary_allergies' => $step4['primary_allergies'] ?? null,
                'primary_medical_condition' => $step4['primary_medical_condition'] ?? null,
                'secondary_name' => $step4['secondary_name'] ?? null,
                'secondary_birthday' => $step4['secondary_birthday'] ?? null,
                'secondary_gender' => $step4['secondary_gender'] ?? null,
                'secondary_phone' => $step4['secondary_phone'] ?? null,
                'secondary_work' => $step4['secondary_work'] ?? null,
                'secondary_allergies' => $step4['secondary_allergies'] ?? null,
                'secondary_medical_condition' => $step4['secondary_medical_condition'] ?? null,
                'family_members' => $step5['family_members'] ?? [],
            ];

            // Create pre-registration record for senior citizen
            $preRegistration = PreRegistration::create([
                // Step 1 data
                'type_of_resident' => $step1['type_of_resident'],
                'first_name' => $step1['first_name'],
                'middle_name' => $step1['middle_name'] ?? null,
                'last_name' => $step1['last_name'],
                'suffix' => $step1['suffix'] ?? null,
                'birthplace' => $step1['birthplace'],
                'birthdate' => $step1['birthdate'],
                'sex' => $step1['sex'],
                'civil_status' => $step1['civil_status'],

                // Step 2 data
                'citizenship_type' => $step2['citizenship_type'],
                'citizenship_country' => $step2['citizenship_country'] ?? null,
                'profession_occupation' => $step2['profession_occupation'] ?? null,
                'monthly_income' => $step2['monthly_income'] ?? null,
                'contact_number' => $step2['contact_number'],
                'email_address' => $step2['email_address'],
                'religion' => $step2['religion'] ?? null,
                'educational_attainment' => $step2['educational_attainment'] ?? null,
                'education_status' => $step2['education_status'] ?? null,
                'address' => $step2['address'],

                // Step 3 data
                'philsys_id' => $step3['philsys_id'] ?? null,
                'population_sectors' => json_encode(['Senior Citizen']),
                'mother_first_name' => $step3['mother_first_name'] ?? null,
                'mother_middle_name' => $step3['mother_middle_name'] ?? null,
                'mother_last_name' => $step3['mother_last_name'] ?? null,

                // Files
                'photo' => $photoFilename,
                'signature' => $signatureFilename,
                'status' => 'pending',

                // Senior citizen info and household data (stored as JSON)
                'senior_info' => json_encode($seniorInfo),
                'household_info' => json_encode($householdData),
            ]);

            // Clear session data
            Session::forget('senior_registration');

            return redirect()->route('public.senior-registration.success')
                ->with('success', 'Your senior citizen registration has been submitted successfully! You will receive your Senior ID via email once approved.')
                ->with('registration_id', $preRegistration->id)
                ->with('is_senior', true);

        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Senior registration submission error: ' . $e->getMessage());
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
     * Show success page after senior registration.
     */
    public function success()
    {
        if (!session()->has('success')) {
            return redirect()->route('public.senior-registration.step1');
        }

        return view('public.senior-registration.success');
    }

    /**
     * Check senior registration status by email.
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

            return view('public.senior-registration.status', compact('registration'));
        }

        return view('public.senior-registration.check-status');
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
        $filename = 'senior_prereg_' . time() . '_' . Str::random(10) . '.jpg';

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
        $filename = 'senior_prereg_sig_' . time() . '_' . Str::random(10) . '.png';

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
}
