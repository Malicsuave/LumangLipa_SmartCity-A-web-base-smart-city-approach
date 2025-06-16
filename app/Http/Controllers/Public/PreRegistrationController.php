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

class PreRegistrationController extends Controller
{
    /**
     * Show Step 1: Personal Information
     */
    public function createStep1()
    {
        return view('public.pre-registration.step1');
    }

    /**
     * Store Step 1: Personal Information
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'type_of_resident' => 'required|in:Permanent,Temporary,Boarder/Transient',
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'suffix' => ['nullable', 'string', 'max:10', 'regex:/^[a-zA-Z\s\.]+$/'],
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'middle_name.regex' => 'Middle name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and dots.',
            'birthdate.before_or_equal' => 'Birthdate cannot be in the future.',
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
            return redirect()->route('public.pre-registration.step1')
                ->with('error', 'Please complete step 1 first');
        }

        return view('public.pre-registration.step2');
    }

    /**
     * Store Step 2: Contact & Education Information
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'citizenship_type' => 'required|string|in:FILIPINO,Dual Citizen,Foreigner',
            'citizenship_country' => 'nullable|required_if:citizenship_type,Dual Citizen,Foreigner|string|max:100',
            'profession_occupation' => 'required|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            'contact_number' => 'required|numeric|digits:11',
            'email_address' => [
                'required',
                'email',
                'max:255',
                Rule::unique('pre_registrations', 'email_address'),
                Rule::unique('residents', 'email_address')
            ],
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'required|string|max:100',
            'education_status' => 'required|in:Studying,Graduated,Stopped Schooling,Not Applicable',
            'address' => 'required|string|max:255',
        ], [
            'contact_number.digits' => 'Contact number must be exactly 11 digits.',
            'email_address.unique' => 'This email address is already registered or pending registration.',
        ]);

        Session::put('pre_registration.step2', $validated);
        return redirect()->route('public.pre-registration.step3');
    }

    /**
     * Show Step 3: Additional Information & Population Sectors
     */
    public function createStep3()
    {
        if (!Session::has('pre_registration.step2')) {
            return redirect()->route('public.pre-registration.step2')
                ->with('error', 'Please complete step 2 first');
        }

        $populationSectors = [
            'Labor Force',
            'Overseas Filipino Worker',
            'Solo Parent',
            'Person with Disability',
            'Indigenous People',
            'Employed',
            'Self-employed (including businessman/women)',
            'Unemployed',
            'Student',
            'Out of school children (6-14 years old)',
            'Out of School Youth (15-24 years old)',
            'Not applicable'
        ];

        return view('public.pre-registration.step3', compact('populationSectors'));
    }

    /**
     * Store Step 3: Additional Information & Population Sectors
     */
    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'philsys_id' => 'nullable|string|max:50',
            'population_sectors' => 'nullable|array',
            'population_sectors.*' => 'string',
            'mother_first_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'mother_middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'mother_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
        ]);

        Session::put('pre_registration.step3', $validated);

        // Check if registrant is a senior citizen (60 years or older)
        $birthdate = Session::get('pre_registration.step1.birthdate');
        $age = Carbon::parse($birthdate)->age;

        if ($age >= 60) {
            return redirect()->route('public.pre-registration.step4-senior');
        } else {
            return redirect()->route('public.pre-registration.step4');
        }
    }

    /**
     * Show Step 4-Senior: Senior Citizen Information (Only for ages 60+)
     */
    public function createStep4Senior()
    {
        if (!Session::has('pre_registration.step3')) {
            return redirect()->route('public.pre-registration.step3')
                ->with('error', 'Please complete step 3 first');
        }

        // Verify the person is actually a senior citizen
        $birthdate = Session::get('pre_registration.step1.birthdate');
        $age = Carbon::parse($birthdate)->age;

        if ($age < 60) {
            return redirect()->route('public.pre-registration.step4');
        }

        return view('public.pre-registration.step4-senior');
    }

    /**
     * Store Step 4-Senior: Senior Citizen Information
     */
    public function storeStep4Senior(Request $request)
    {
        $validated = $request->validate([
            'pension_type' => 'nullable|string|max:100',
            'pension_amount' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|numeric|digits:11',
            'health_conditions' => 'nullable|string|max:500',
            'medications' => 'nullable|string|max:500',
            'living_arrangement' => 'nullable|in:Alone,With Family,With Caregiver,Assisted Living',
            'mobility_status' => 'nullable|in:Independent,Needs Assistance,Wheelchair Bound,Bedridden',
        ]);

        Session::put('pre_registration.step4_senior', $validated);
        return redirect()->route('public.pre-registration.step4');
    }

    /**
     * Show Step 4: Photo & Signature Upload
     */
    public function createStep4()
    {
        if (!Session::has('pre_registration.step3')) {
            return redirect()->route('public.pre-registration.step3')
                ->with('error', 'Please complete step 3 first');
        }

        return view('public.pre-registration.step4');
    }

    /**
     * Store Step 4: Photo & Signature Upload
     */
    public function storeStep4(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5000',
            'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'terms_accepted' => 'required|accepted',
        ], [
            'photo.required' => 'Photo is required for ID generation.',
            'photo.max' => 'Photo file size must not exceed 5MB.',
            'signature.max' => 'Signature file size must not exceed 2MB.',
            'terms_accepted.required' => 'You must accept the terms and conditions to proceed.',
        ]);

        // Store files temporarily in session as base64
        $files = [];
        
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $files['photo'] = [
                'name' => $photoFile->getClientOriginalName(),
                'data' => base64_encode(file_get_contents($photoFile->getPathname())),
                'mime' => $photoFile->getMimeType()
            ];
        }

        if ($request->hasFile('signature')) {
            $signatureFile = $request->file('signature');
            $files['signature'] = [
                'name' => $signatureFile->getClientOriginalName(),
                'data' => base64_encode(file_get_contents($signatureFile->getPathname())),
                'mime' => $signatureFile->getMimeType()
            ];
        }

        $files['terms_accepted'] = $validated['terms_accepted'];
        
        Session::put('pre_registration.step4', $files);
        return redirect()->route('public.pre-registration.review');
    }

    /**
     * Show Review Page
     */
    public function createReview()
    {
        if (!Session::has('pre_registration.step4')) {
            return redirect()->route('public.pre-registration.step4')
                ->with('error', 'Please complete step 4 first');
        }

        // Check if person is senior citizen
        $birthdate = Session::get('pre_registration.step1.birthdate');
        $age = Carbon::parse($birthdate)->age;
        $isSenior = $age >= 60;

        return view('public.pre-registration.review', compact('isSenior'));
    }

    /**
     * Final Submit - Store all data
     */
    public function store(Request $request)
    {
        $request->validate([
            'final_confirmation' => 'required|accepted'
        ]);

        // Check if all session data exists
        if (!Session::has('pre_registration.step1') || 
            !Session::has('pre_registration.step2') || 
            !Session::has('pre_registration.step3') ||
            !Session::has('pre_registration.step4')) {
            return redirect()->route('public.pre-registration.step1')
                ->with('error', 'Registration data is incomplete. Please start again.');
        }

        try {
            // Handle photo upload
            $photoFilename = null;
            $photoFile = Session::get('pre_registration.step4.photo');
            if ($photoFile) {
                $photoFilename = $this->processPhoto($photoFile);
            }

            // Handle optional signature upload
            $signatureFilename = null;
            $signatureFile = Session::get('pre_registration.step4.signature');
            if ($signatureFile) {
                $signatureFilename = $this->processSignature($signatureFile);
            }

            // Get all session data
            $step1 = Session::get('pre_registration.step1');
            $step2 = Session::get('pre_registration.step2');
            $step3 = Session::get('pre_registration.step3');
            $step4Senior = Session::get('pre_registration.step4_senior', []);

            // Check if person is senior citizen
            $age = Carbon::parse($step1['birthdate'])->age;
            $isSenior = $age >= 60;

            // Add senior citizen to population sectors if they are 60+
            $populationSectors = $step3['population_sectors'] ?? [];
            if ($isSenior && !in_array('Senior Citizen', $populationSectors)) {
                $populationSectors[] = 'Senior Citizen';
            }

            // Create pre-registration record
            $preRegistration = PreRegistration::create([
                // Step 1 data
                'type_of_resident' => $step1['type_of_resident'],
                'first_name' => $step1['first_name'],
                'middle_name' => $step1['middle_name'],
                'last_name' => $step1['last_name'],
                'suffix' => $step1['suffix'],
                'birthplace' => $step1['birthplace'],
                'birthdate' => $step1['birthdate'],
                'sex' => $step1['sex'],
                'civil_status' => $step1['civil_status'],
                
                // Step 2 data
                'citizenship_type' => $step2['citizenship_type'],
                'citizenship_country' => $step2['citizenship_country'],
                'profession_occupation' => $step2['profession_occupation'],
                'monthly_income' => $step2['monthly_income'],
                'contact_number' => $step2['contact_number'],
                'email_address' => $step2['email_address'],
                'religion' => $step2['religion'],
                'educational_attainment' => $step2['educational_attainment'],
                'education_status' => $step2['education_status'],
                'address' => $step2['address'],
                
                // Step 3 data
                'philsys_id' => $step3['philsys_id'],
                'population_sectors' => $populationSectors,
                'mother_first_name' => $step3['mother_first_name'],
                'mother_middle_name' => $step3['mother_middle_name'],
                'mother_last_name' => $step3['mother_last_name'],
                
                // Files
                'photo' => $photoFilename,
                'signature' => $signatureFilename,
                'status' => 'pending',
                
                // Senior citizen info (stored as JSON for now)
                'senior_info' => $isSenior ? json_encode($step4Senior) : null,
            ]);

            // Clear session data
            Session::forget('pre_registration');

            return redirect()->route('public.pre-registration.success')
                ->with('success', $isSenior ? 
                    'Your senior citizen registration has been submitted successfully! You will receive your Senior ID via email once approved.' :
                    'Your registration has been submitted successfully! You will receive your ID via email once approved.')
                ->with('registration_id', $preRegistration->id)
                ->with('is_senior', $isSenior);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was an error processing your registration. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show success page after registration.
     */
    public function success()
    {
        if (!session()->has('success')) {
            return redirect()->route('public.pre-registration.create');
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
}
