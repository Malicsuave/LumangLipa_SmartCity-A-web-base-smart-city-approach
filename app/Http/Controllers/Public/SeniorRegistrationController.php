<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SeniorRegistrationController extends Controller
{
    /**
     * Show Step 1: Personal Information
     */
    public function createStep1()
    {
        $step1 = Session::get('senior_registration.step1', []);
        return view('public.senior-registration.step1', compact('step1'));
    }

    /**
     * Store Step 1: Personal Information
     */
    public function storeStep1(Request $request)
    {
        $request->validate([
            'type_of_resident' => 'required|in:Non-Migrant,Migrant,Transient',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(60)->format('Y-m-d'),
            'birthplace' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'civil_status' => 'required|in:Single,Married,Widowed,Divorced,Separated',
            'citizenship_type' => 'nullable|in:FILIPINO,DUAL,NATURALIZED,FOREIGN',
            'citizenship_country' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'educational_attainment' => 'nullable|string|max:255',
            'education_status' => 'nullable|string|max:255',
            'profession_occupation' => 'nullable|string|max:255',
        ]);

        // Validate age is 60 or above
        $birthdate = Carbon::parse($request->birthdate);
        if ($birthdate->age < 60) {
            return redirect()->back()
                ->withErrors(['birthdate' => 'You must be at least 60 years old to register as a senior citizen.'])
                ->withInput();
        }

        Session::put('senior_registration.step1', $request->except('_token'));
        
        return redirect()->route('public.senior-registration.step2')
            ->with('success', 'Personal information saved successfully!');
    }

    /**
     * Show Step 2: Contact Information
     */
    public function createStep2()
    {
        if (!Session::has('senior_registration.step1')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Please complete Step 1 first.');
        }
        
        $step2 = Session::get('senior_registration.step2', []);
        return view('public.senior-registration.step2', compact('step2'));
    }

    /**
     * Store Step 2: Contact Information
     */
    public function storeStep2(Request $request)
    {
        $request->validate([
            'contact_number' => 'required|numeric|digits:11|regex:/^09\d{9}$/',
            'email_address' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:255',
            'emergency_contact_number' => 'required|numeric|digits:11|regex:/^09\d{9}$/',
            'emergency_contact_address' => 'required|string|max:500',
        ], [
            'contact_number.required' => 'The contact number field is required.',
            'contact_number.numeric' => 'The contact number must contain only numbers.',
            'contact_number.digits' => 'The contact number must be exactly 11 digits.',
            'contact_number.regex' => 'The contact number must be a valid Philippine mobile number (09XXXXXXXXX).',
            'emergency_contact_name.required' => 'The emergency contact name is required.',
            'emergency_contact_relationship.required' => 'The emergency contact relationship is required.',
            'emergency_contact_number.required' => 'The emergency contact number is required.',
            'emergency_contact_number.numeric' => 'The emergency contact number must contain only numbers.',
            'emergency_contact_number.digits' => 'The emergency contact number must be exactly 11 digits.',
            'emergency_contact_number.regex' => 'The emergency contact number must be a valid Philippine mobile number (09XXXXXXXXX).',
            'emergency_contact_address.required' => 'The emergency contact address is required.',
        ]);

        Session::put('senior_registration.step2', $request->except('_token'));
        
        return redirect()->route('public.senior-registration.step3')
            ->with('success', 'Contact information saved successfully!');
    }

    /**
     * Show Step 3: Senior Citizen Specific Information
     */
    public function createStep3()
    {
        if (!Session::has('senior_registration.step1') || !Session::has('senior_registration.step2')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Please complete previous steps first.');
        }
        
        $step3 = Session::get('senior_registration.step3', []);
        return view('public.senior-registration.step3', compact('step3'));
    }

    /**
     * Store Step 3: Photo & Documents Upload
     */
    public function storeStep3(Request $request)
    {
        // Check if files already exist in session
        $existingStep3 = Session::get('senior_registration.step3', []);
        $hasPhoto = isset($existingStep3['photo']) && $existingStep3['photo'];
        $hasProofOfResidency = isset($existingStep3['proof_of_residency']) && $existingStep3['proof_of_residency'];
        
        $request->validate([
            'photo' => ($hasPhoto ? 'nullable' : 'required') . '|image|mimes:jpeg,jpg,png|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
            'proof_of_residency' => ($hasProofOfResidency ? 'nullable' : 'required') . '|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'captured_photo_data' => 'nullable|string',
        ], [
            'photo.required' => 'The photo field is required.',
            'photo.image' => 'The photo must be an image.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, jpg, png.',
            'photo.max' => 'The photo may not be greater than 2MB.',
            'proof_of_residency.required' => 'The proof of residency document is required.',
            'proof_of_residency.file' => 'The proof of residency must be a file.',
            'proof_of_residency.mimes' => 'The proof of residency must be a file of type: jpeg, jpg, png, pdf.',
            'proof_of_residency.max' => 'The proof of residency may not be greater than 5MB.',
        ]);

        // Start with existing step data to preserve previously uploaded files
        $stepData = $existingStep3;

        // Handle photo upload (either file upload or captured photo)
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('senior-registrations/photos', 'public');
            $stepData['photo'] = $photoPath;
        } elseif ($request->filled('captured_photo_data')) {
            // Handle captured photo from camera
            $capturedData = $request->captured_photo_data;
            if (strpos($capturedData, 'data:image') === 0) {
                $image = str_replace('data:image/png;base64,', '', $capturedData);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);
                
                $filename = 'photo_' . time() . '.png';
                $photoPath = 'senior-registrations/photos/' . $filename;
                Storage::disk('public')->put($photoPath, $imageData);
                $stepData['photo'] = $photoPath;
            }
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $signaturePath = $signature->store('senior-registrations/signatures', 'public');
            $stepData['signature'] = $signaturePath;
        }

        // Handle proof of residency upload
        if ($request->hasFile('proof_of_residency')) {
            $proof = $request->file('proof_of_residency');
            $proofPath = $proof->store('senior-registrations/proof-of-residency', 'public');
            $stepData['proof_of_residency'] = $proofPath;
        }

        Session::put('senior_registration.step3', $stepData);
        
        return redirect()->route('public.senior-registration.step4')
            ->with('success', 'Documents uploaded successfully!');
    }

    /**
     * Show Step 4: Senior Citizen Specific Information
     */
    public function createStep4()
    {
        if (!Session::has('senior_registration.step1') || 
            !Session::has('senior_registration.step2') || 
            !Session::has('senior_registration.step3')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Please complete previous steps first.');
        }
        
        $step4 = Session::get('senior_registration.step4', []);
        return view('public.senior-registration.step4', compact('step4'));
    }

    /**
     * Store Step 4: Senior Citizen Specific Information
     */
    public function storeStep4(Request $request)
    {
        $request->validate([
            // Health Information
            'health_condition' => 'nullable|string|in:excellent,good,fair,poor,critical',
            'mobility_status' => 'nullable|string|in:independent,assisted,wheelchair,bedridden',
            'medical_conditions' => 'nullable|string|max:2000',
            
            // Pension Information
            'receiving_pension' => 'nullable|boolean',
            'pension_type' => 'nullable|required_if:receiving_pension,1|string|in:SSS,GSIS,Government Employee,Private Company,Social Pension,Other',
            'pension_amount' => 'nullable|numeric|min:0|max:99999999.99',
            
            // PhilHealth
            'has_philhealth' => 'nullable|boolean',
            'philhealth_number' => 'nullable|required_if:has_philhealth,1|string|max:14',
            
            // Senior Discount Card
            'has_senior_discount_card' => 'nullable|boolean',
            
            // Services
            'services' => 'nullable|array',
            'services.*' => 'string|in:healthcare,financial_assistance,education,legal_assistance,transportation,discount_privileges,emergency_response',
            
            // Additional Notes
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get all request data except _token
        $step4Data = $request->except('_token');
        
        // Log the services data for debugging
        Log::info('Step 4 Services Data:', [
            'services' => $request->input('services'),
            'all_data' => $step4Data
        ]);
        
        Session::put('senior_registration.step4', $step4Data);
        
        return redirect()->route('public.senior-registration.review')
            ->with('success', 'Senior citizen information saved successfully!');
    }

    /**
     * Show Review and Confirmation
     */
    public function createReview()
    {
        if (!Session::has('senior_registration.step1') || 
            !Session::has('senior_registration.step2') || 
            !Session::has('senior_registration.step3') ||
            !Session::has('senior_registration.step4')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Please complete all previous steps first.');
        }
        
        $step1 = Session::get('senior_registration.step1');
        $step2 = Session::get('senior_registration.step2');
        $step3 = Session::get('senior_registration.step3');
        $step4 = Session::get('senior_registration.step4');
        
        return view('public.senior-registration.review', compact('step1', 'step2', 'step3', 'step4'));
    }

    /**
     * Submit the complete senior citizen pre-registration
     */
    public function store(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|accepted',
        ]);

        // Check if all session data exists
        if (!Session::has('senior_registration.step1') || 
            !Session::has('senior_registration.step2') || 
            !Session::has('senior_registration.step3') ||
            !Session::has('senior_registration.step4')) {
            return redirect()->route('public.senior-registration.step1')
                ->with('error', 'Registration data is incomplete. Please start from Step 1.');
        }

        $step1 = Session::get('senior_registration.step1');
        $step2 = Session::get('senior_registration.step2');
        $step3 = Session::get('senior_registration.step3');
        $step4 = Session::get('senior_registration.step4');

        DB::beginTransaction();
        try {
            // Create senior pre-registration record (registration_id will be auto-generated by model)
            $seniorRegistration = \App\Models\SeniorPreRegistration::create([
                
                // Step 1: Personal Information
                'type_of_resident' => $step1['type_of_resident'],
                'first_name' => $step1['first_name'],
                'middle_name' => $step1['middle_name'] ?? null,
                'last_name' => $step1['last_name'],
                'suffix' => $step1['suffix'] ?? null,
                'birthdate' => $step1['birthdate'],
                'birthplace' => $step1['birthplace'],
                'sex' => $step1['sex'],
                'civil_status' => $step1['civil_status'],
                'citizenship_type' => $step1['citizenship_type'] ?? null,
                'citizenship_country' => $step1['citizenship_country'] ?? null,
                'nationality' => $step1['nationality'] ?? null,
                'religion' => $step1['religion'] ?? null,
                'educational_attainment' => $step1['educational_attainment'] ?? null,
                'education_status' => $step1['education_status'] ?? null,
                'profession_occupation' => $step1['profession_occupation'] ?? null,
                
                // Step 2: Contact & Address Information
                'contact_number' => $step2['contact_number'],
                'email_address' => $step2['email_address'] ?? null,
                'address' => $step2['address'],
                'emergency_contact_name' => $step2['emergency_contact_name'],
                'emergency_contact_relationship' => $step2['emergency_contact_relationship'],
                'emergency_contact_number' => $step2['emergency_contact_number'],
                'emergency_contact_address' => $step2['emergency_contact_address'] ?? null,
                
                // Step 3: Photo, Signature & Documents
                'photo' => $step3['photo'] ?? null,
                'signature' => $step3['signature'] ?? null,
                'proof_of_residency' => $step3['proof_of_residency'] ?? null,
                
                // Step 4: Senior Citizen Specific Information
                'health_condition' => $step4['health_condition'] ?? null,
                'mobility_status' => $step4['mobility_status'] ?? null,
                'medical_conditions' => $step4['medical_conditions'] ?? null,
                'receiving_pension' => isset($step4['receiving_pension']) && $step4['receiving_pension'] == '1',
                'pension_type' => $step4['pension_type'] ?? null,
                'pension_amount' => $step4['pension_amount'] ?? null,
                'has_philhealth' => isset($step4['has_philhealth']) && $step4['has_philhealth'] == '1',
                'philhealth_number' => $step4['philhealth_number'] ?? null,
                'has_senior_discount_card' => isset($step4['has_senior_discount_card']) && $step4['has_senior_discount_card'] == '1',
                'services' => $step4['services'] ?? [],
                'notes' => $step4['notes'] ?? null,
                
                // System fields
                'status' => 'pending',
            ]);

            Log::info('Senior citizen registration submitted successfully', [
                'registration_id' => $seniorRegistration->id,
                'name' => $step1['first_name'] . ' ' . $step1['last_name']
            ]);

            DB::commit();

            // Clear session data
            Session::forget([
                'senior_registration.step1',
                'senior_registration.step2', 
                'senior_registration.step3',
                'senior_registration.step4',
                'temp_photo_preview',
                'temp_signature_preview',
                'temp_proof_preview'
            ]);

            return redirect()->route('public.senior-registration.success')
                ->with('success', 'Your senior citizen pre-registration has been submitted successfully!')
                ->with('registration_id', $seniorRegistration->registration_id);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Senior citizen pre-registration failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred while submitting your registration. Please try again.');
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('public.senior-registration.success');
    }
}
