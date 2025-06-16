<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class PreRegistrationController extends Controller
{
    /**
     * Show the pre-registration form.
     */
    public function create()
    {
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

        return view('public.pre-registration.create', compact('populationSectors'));
    }

    /**
     * Store the pre-registration data.
     */
    public function store(Request $request)
    {
        // Use the same validation rules as the admin registration
        $validated = $request->validate([
            // Personal Information - Step 1 equivalent
            'type_of_resident' => 'required|in:Permanent,Temporary,Boarder/Transient',
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'suffix' => ['nullable', 'string', 'max:10', 'regex:/^[a-zA-Z\s\.]+$/'],
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Divorced,Widowed,Separated',

            // Citizenship & Contact Information - Step 2 equivalent
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
            'philsys_id' => 'nullable|string|max:50',
            'population_sectors' => 'nullable|array',
            'population_sectors.*' => 'string',

            // Parent Information
            'mother_first_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'mother_middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'mother_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],

            // Required Photo Upload
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5000',
            
            // Optional Signature Upload
            'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',

            // Terms and Conditions
            'terms_accepted' => 'required|accepted',
        ], [
            // Custom error messages
            'first_name.regex' => 'First name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'middle_name.regex' => 'Middle name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'suffix.regex' => 'Suffix can only contain letters, spaces, and dots.',
            'contact_number.digits' => 'Contact number must be exactly 11 digits.',
            'email_address.unique' => 'This email address is already registered or pending registration.',
            'photo.required' => 'Photo is required for ID generation.',
            'photo.max' => 'Photo file size must not exceed 5MB.',
            'signature.max' => 'Signature file size must not exceed 2MB.',
            'terms_accepted.required' => 'You must accept the terms and conditions to proceed.',
        ]);

        try {
            // Handle photo upload
            $photoFilename = null;
            if ($request->hasFile('photo')) {
                $photoFilename = $this->processPhoto($request->file('photo'));
            }

            // Handle optional signature upload
            $signatureFilename = null;
            if ($request->hasFile('signature')) {
                $signatureFilename = $this->processSignature($request->file('signature'));
            }

            // Create pre-registration record
            $preRegistration = PreRegistration::create([
                'type_of_resident' => $validated['type_of_resident'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'],
                'birthplace' => $validated['birthplace'],
                'birthdate' => $validated['birthdate'],
                'sex' => $validated['sex'],
                'civil_status' => $validated['civil_status'],
                'citizenship_type' => $validated['citizenship_type'],
                'citizenship_country' => $validated['citizenship_country'],
                'profession_occupation' => $validated['profession_occupation'],
                'monthly_income' => $validated['monthly_income'],
                'contact_number' => $validated['contact_number'],
                'email_address' => $validated['email_address'],
                'religion' => $validated['religion'],
                'educational_attainment' => $validated['educational_attainment'],
                'education_status' => $validated['education_status'],
                'address' => $validated['address'],
                'philsys_id' => $validated['philsys_id'],
                'population_sectors' => $validated['population_sectors'],
                'mother_first_name' => $validated['mother_first_name'],
                'mother_middle_name' => $validated['mother_middle_name'],
                'mother_last_name' => $validated['mother_last_name'],
                'photo' => $photoFilename,
                'signature' => $signatureFilename,
                'status' => 'pending',
            ]);

            return redirect()->route('public.pre-registration.success')
                ->with('success', 'Your registration has been submitted successfully! You will receive an email notification once your application is reviewed.')
                ->with('registration_id', $preRegistration->id);

        } catch (\Exception $e) {
            // Clean up uploaded files if there was an error
            if (isset($photoFilename) && $photoFilename) {
                Storage::disk('public')->delete('pre-registrations/photos/' . $photoFilename);
            }
            if (isset($signatureFilename) && $signatureFilename) {
                Storage::disk('public')->delete('pre-registrations/signatures/' . $signatureFilename);
            }

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
     * Process and save photo.
     */
    private function processPhoto($file)
    {
        $image = Image::make($file);
        
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
     * Process and save signature.
     */
    private function processSignature($file)
    {
        $image = Image::make($file);
        
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
