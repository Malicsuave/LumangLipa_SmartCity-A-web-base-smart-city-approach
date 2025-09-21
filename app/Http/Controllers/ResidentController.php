<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\FamilyMember;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ResidentController extends Controller
{
    /**
     * Display a listing of the residents.
     */
    public function index(Request $request)
    {
        $query = Resident::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('barangay_id', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by resident type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type_of_resident', $request->type);
        }

        // Filter by civil status
        if ($request->has('civil_status') && !empty($request->civil_status)) {
            $query->where('civil_status', $request->civil_status);
        }

        // Filter by gender
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('sex', $request->gender);
        }

        // Filter by age group
        if ($request->has('age_group') && !empty($request->age_group)) {
            $now = Carbon::now();
            switch ($request->age_group) {
                case '0-17':
                    $query->whereDate('birthdate', '>', $now->copy()->subYears(18));
                    break;
                case '18-59':
                    $query->whereDate('birthdate', '<=', $now->copy()->subYears(18))
                          ->whereDate('birthdate', '>', $now->copy()->subYears(60));
                    break;
                case '60+':
                    $query->whereDate('birthdate', '<=', $now->copy()->subYears(60));
                    break;
            }
        }

        // Filter by educational attainment
        if ($request->has('education') && !empty($request->education)) {
            $query->where('educational_attainment', $request->education);
        }

        // Filter by citizenship type
        if ($request->has('citizenship') && !empty($request->citizenship)) {
            $query->where('citizenship_type', $request->citizenship);
        }

        // Filter by ID status
        if ($request->has('id_status') && !empty($request->id_status)) {
            $query->where('id_status', $request->id_status);
        }

        // Filter by population sector
        if ($request->has('population_sector') && !empty($request->population_sector)) {
            $query->whereJsonContains('population_sectors', $request->population_sector);
        }

        // Filter by income range
        if ($request->has('income_range') && !empty($request->income_range)) {
            switch ($request->income_range) {
                case 'low':
                    $query->where('monthly_income', '<', 15000);
                    break;
                case 'middle':
                    $query->whereBetween('monthly_income', [15000, 50000]);
                    break;
                case 'high':
                    $query->where('monthly_income', '>', 50000);
                    break;
                case 'no_income':
                    $query->whereNull('monthly_income')->orWhere('monthly_income', 0);
                    break;
            }
        }

        // Sorting functionality
        $sortField = $request->get('sort', 'last_name');
        $sortDirection = $request->get('direction', 'asc');
        
        // Define allowed sort fields for security
        $allowedSortFields = [
            'barangay_id', 'first_name', 'last_name', 'sex', 'civil_status', 
            'contact_number', 'type_of_resident', 'birthdate', 'created_at'
        ];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('last_name')->orderBy('first_name');
        }

        // Get all residents and let DataTables handle pagination
        $residents = $query->get();

        return view('admin.residents', compact('residents'));
    }

    /**
     * Show the form for creating a new resident (Step 1).
     */
    public function create()
    {
        // Clear any previous session data
        Session::forget('registration');
        
        return view('admin.residents.registration.step1');
    }

    /**
     * Show the first step of resident registration.
     */
    public function createStep1()
    {
        // If navigating back to step 1, we preserve existing session data
        // This allows users to edit step 1 without losing data from other steps
        return view('admin.residents.registration.step1');
    }

    /**
     * Store the first step of resident registration.
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'type_of_resident' => 'required|in:Non-Migrant,Migrant,Transient',
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'sex' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced',
        ], [
            'type_of_resident.required' => 'Please select the type of resident.',
            'type_of_resident.in' => 'Please select a valid resident type.',
            'last_name.required' => 'The last name field is required.',
            'last_name.max' => 'The last name must not exceed 100 characters.',
            'first_name.required' => 'The first name field is required.',
            'first_name.max' => 'The first name must not exceed 100 characters.',
            'middle_name.max' => 'The middle name must not exceed 100 characters.',
            'suffix.max' => 'The suffix must not exceed 10 characters.',
            'birthplace.required' => 'The birthplace field is required.',
            'birthdate.required' => 'The birthdate field is required.',
            'birthdate.date' => 'The birthdate must be a valid date format.',
            'birthdate.before_or_equal' => 'The birthdate cannot be in the future.',
            'sex.required' => 'Please select a gender.',
            'sex.in' => 'Please select a valid gender.',
            'civil_status.required' => 'Please select a civil status.',
            'civil_status.in' => 'Please select a valid civil status.'
        ]);

        Session::put('registration.step1', $validated);
        
        return redirect()->route('admin.residents.create.step2');
    }

    /**
     * Show the second step of resident registration.
     */
    public function createStep2()
    {
        // Check if step 1 has been completed
        if (!Session::has('registration.step1')) {
            return redirect()->route('admin.residents.create')
                ->with('error', 'Please complete step 1 first');
        }

        // We're allowing the user to go back and edit, so we don't reset any subsequent steps
        return view('admin.residents.registration.step2');
    }

    /**
     * Store the second step of resident registration.
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'contact_number' => 'required|numeric|digits:11',
            'email_address' => 'nullable|email|max:255',
            'current_address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|string|max:100',
            'emergency_contact_number' => 'required|numeric|digits:11',
            'emergency_contact_address' => 'nullable|string|max:500',
        ], 
        [
            'contact_number.required' => 'The contact number field is required.',
            'contact_number.numeric' => 'The contact number must contain only numbers.',
            'contact_number.digits' => 'The contact number must be exactly 11 digits.',
            'email_address.email' => 'Please enter a valid email address.',
            'current_address.required' => 'The current address field is required.',
            'emergency_contact_name.required' => 'The emergency contact name is required.',
            'emergency_contact_relationship.required' => 'Please select the relationship to emergency contact.',
            'emergency_contact_number.required' => 'The emergency contact number is required.',
            'emergency_contact_number.numeric' => 'The emergency contact number must contain only numbers.',
            'emergency_contact_number.digits' => 'The emergency contact number must be exactly 11 digits.',
        ]);

        // Store validated data in session
        Session::put('registration.step2', $validated);

        return redirect()->route('admin.residents.create.step3');
    }

    /**
     * Show the third step of resident registration.
     */
    public function createStep3()
    {
        // Check if previous steps have been completed
        if (!Session::has('registration.step2')) {
            return redirect()->route('admin.residents.create.step2')
                ->with('error', 'Please complete step 2 first');
        }

        // We're allowing the user to go back and edit, so we don't reset any subsequent steps
        return view('admin.residents.registration.step3');
    }

    /**
     * Store the third step of resident registration.
     */
    public function storeStep3(Request $request)
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
            
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_work' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|numeric|digits:11',
        ], [
            'primary_name.required' => 'The primary person\'s name is required.',
            'primary_birthday.required' => 'The primary person\'s birthday is required.',
            'primary_birthday.date' => 'The birthday must be a valid date format.',
            'primary_birthday.before_or_equal' => 'The birthday cannot be in the future.',
            'primary_gender.required' => 'Please select the primary person\'s gender.',
            'primary_gender.in' => 'Please select a valid gender.',
            'primary_phone.required' => 'The primary phone number is required.',
            'primary_phone.numeric' => 'The phone number must contain only numbers.',
            'primary_phone.digits' => 'The primary phone number must be exactly 11 digits.',
            'secondary_phone.numeric' => 'The phone number must contain only numbers.',
            'secondary_phone.digits' => 'The secondary phone number must be exactly 11 digits.',
            'emergency_phone.numeric' => 'The emergency contact phone number must contain only numbers.',
            'emergency_phone.digits' => 'The emergency contact phone number must be exactly 11 digits.',
            'secondary_birthday.date' => 'The birthday must be a valid date format.',
            'secondary_birthday.before_or_equal' => 'The birthday cannot be in the future.',
            'secondary_gender.in' => 'Please select a valid gender.',
        ]);

        // Store data in session
        Session::put('registration.step3', $validated);

        // Check if the registered person is 60 or older to determine next step
        $birthdate = Session::get('registration.step1.birthdate');
        $age = Carbon::parse($birthdate)->age;

        // If 60 or older, go to senior citizen step, otherwise go to family members step
        if ($age >= 60) {
            return redirect()->route('admin.residents.create.step4-senior');
        } else {
            return redirect()->route('admin.residents.create.step4');
        }
    }

    /**
     * Show the fourth step of resident registration.
     */
    public function createStep4()
    {
        // Check if previous steps have been completed
        if (!Session::has('registration.step3')) {
            return redirect()->route('admin.residents.create.step3')
                ->with('error', 'Please complete step 3 first');
        }

        // We're allowing the user to go back and edit, so we don't reset any subsequent steps
        return view('admin.residents.create.step4');
    }

    /**
     * Store the fourth step of resident registration.
     */
    public function storeStep4(Request $request)
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

        // Store data in session
        Session::put('registration.step4', $validated);
        
        return redirect()->route('admin.residents.create.step5');
    }

    /**
     * Show the fifth step of resident registration.
     */
    public function createStep5()
    {
        // Check if previous steps have been completed
        if (!Session::has('registration.step4')) {
            return redirect()->route('admin.residents.create.step4')
                ->with('error', 'Please complete step 4 first');
        }

        // We're allowing the user to go back and edit, so we don't reset any subsequent steps
        return view('admin.residents.create.step5');
    }

    /**
     * Store the fifth step of resident registration.
     */
    public function storeStep5(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules for step 5 fields
            // Example:
            'additional_info' => 'nullable|string|max:255',
            // Add other validation rules as needed
        ]);

        // Store data in session
        Session::put('registration.step5', $validated);
        
        return redirect()->route('admin.residents.create.review');
    }

    /**
     * Show the review page.
     */
    public function createReview()
    {
        // Check if previous steps have been completed
        if (!Session::has('registration.step4')) {
            return redirect()->route('admin.residents.create.step4')
                ->with('error', 'Please complete step 4 first');
        }

        return view('admin.residents.create.review');
    }

    /**
     * Store a newly created resident in storage.
     */
    public function store(Request $request)
    {
        // Validate confirmation checkbox
        $request->validate([
            'confirmation' => 'required|accepted'
        ]);

        // Check if all session data exists (step3 is optional for basic registration)
        if (!Session::has('registration.step1') || 
            !Session::has('registration.step2')) {
            return redirect()->route('admin.residents.create')
                ->with('error', 'Registration data is incomplete. Please start again.');
        }

        // Start database transaction
        DB::beginTransaction();

        try {
            // Create resident record
            $resident = new Resident();
            
            // Personal info (Step 1)
            $resident->type_of_resident = Session::get('registration.step1.type_of_resident');
            $resident->first_name = Session::get('registration.step1.first_name');
            $resident->middle_name = Session::get('registration.step1.middle_name');
            $resident->last_name = Session::get('registration.step1.last_name');
            $resident->suffix = Session::get('registration.step1.suffix');
            $resident->birthplace = Session::get('registration.step1.birthplace');
            $resident->birthdate = Session::get('registration.step1.birthdate');
            $resident->sex = Session::get('registration.step1.sex');
            $resident->civil_status = Session::get('registration.step1.civil_status');
            
            // Contact info (Step 2)
            $resident->contact_number = Session::get('registration.step2.contact_number');
            $resident->email_address = Session::get('registration.step2.email_address');
            $resident->address = Session::get('registration.step2.current_address'); // Note: session stores as 'current_address'
            
            // Required fields with default values (since our form only covers basic info)
            $resident->citizenship_type = 'FILIPINO'; // Default to Filipino citizenship
            $resident->citizenship_country = null;
            $resident->profession_occupation = null;
            $resident->monthly_income = null;
            $resident->religion = null;
            $resident->educational_attainment = 'not applicable';
            $resident->education_status = 'not applicable';
            $resident->philsys_id = null;
            
            // Auto-assign Senior Citizen if applicable (60+ years old)
            $birthdate = \Carbon\Carbon::parse($resident->birthdate);
            $age = $birthdate->age;
            if ($age >= 60) {
                $resident->population_sectors = ['Senior Citizen'];
            }
            
            // Generate a unique barangay ID using the model's method
            $resident->barangay_id = Resident::generateBarangayId();
            
            // Save the resident
            $resident->save();

            // Create household record (only if step3 contains actual household data)
            if (Session::has('registration.step3.primary_name')) {
                $household = new Household();
                $household->address = $resident->address;
                $household->primary_name = Session::get('registration.step3.primary_name');
                $household->primary_birthday = Session::get('registration.step3.primary_birthday');
                $household->primary_gender = Session::get('registration.step3.primary_gender');
                $household->primary_phone = Session::get('registration.step3.primary_phone');
                $household->primary_work = Session::get('registration.step3.primary_work');
                $household->primary_allergies = Session::get('registration.step3.primary_allergies');
                $household->primary_medical_condition = Session::get('registration.step3.primary_medical_condition');
                
                if (Session::get('registration.step3.secondary_name')) {
                    $household->secondary_name = Session::get('registration.step3.secondary_name');
                    $household->secondary_birthday = Session::get('registration.step3.secondary_birthday');
                    $household->secondary_gender = Session::get('registration.step3.secondary_gender');
                    $household->secondary_phone = Session::get('registration.step3.secondary_phone');
                    $household->secondary_work = Session::get('registration.step3.secondary_work');
                    $household->secondary_allergies = Session::get('registration.step3.secondary_allergies');
                    $household->secondary_medical_condition = Session::get('registration.step3.secondary_medical_condition');
                }
                
                if (Session::get('registration.step3.emergency_contact_name')) {
                    $household->emergency_contact_name = Session::get('registration.step3.emergency_contact_name');
                    $household->emergency_relationship = Session::get('registration.step3.emergency_relationship');
                    $household->emergency_work = Session::get('registration.step3.emergency_work');
                    $household->emergency_phone = Session::get('registration.step3.emergency_phone');
                }
                
                $household->resident_id = $resident->id;
                $household->save();
                
                // Create family members if any (only if household exists)
                if (Session::has('registration.step4.family_members')) {
                    $familyMembers = Session::get('registration.step4.family_members');
                    
                    foreach ($familyMembers as $member) {
                        $familyMember = new FamilyMember();
                        $familyMember->name = $member['name'];
                        $familyMember->relationship = $member['relationship'];
                        $familyMember->related_to = $member['related_to'] ?? null;
                        $familyMember->birthday = $member['birthday'];
                        $familyMember->gender = $member['gender'];
                        $familyMember->work = $member['work'] ?? null;
                        $familyMember->medical_condition = $member['medical_condition'] ?? null;
                        $familyMember->allergies = $member['allergies'] ?? null;
                        $familyMember->household_id = $household->id;
                        $familyMember->resident_id = $resident->id;
                        $familyMember->save();
                    }
                }
            }

            // Check if resident is a senior citizen (60 years or older)
            $birthdate = \Carbon\Carbon::parse($resident->birthdate);
            $age = $birthdate->age;
            
            if ($age >= 60) {
                // Create senior citizen record
                $seniorCitizen = new \App\Models\SeniorCitizen();
                $seniorCitizen->resident_id = $resident->id;
                $seniorCitizen->senior_id_number = \App\Models\SeniorCitizen::generateSeniorIdNumber();
                $seniorCitizen->senior_id_issued_at = now();
                $seniorCitizen->senior_id_expires_at = now()->addYears(5);
                $seniorCitizen->senior_id_status = 'issued';
                
                // Copy health information from household if available
                $seniorCitizen->health_conditions = Session::get('registration.step3.primary_medical_condition') ?? null;
                $seniorCitizen->allergies = Session::get('registration.step3.primary_allergies') ?? null;
                
                // Set emergency contact from household data
                if (Session::get('registration.step3.emergency_contact_name')) {
                    $seniorCitizen->emergency_contact_name = Session::get('registration.step3.emergency_contact_name');
                    $seniorCitizen->emergency_contact_number = Session::get('registration.step3.emergency_phone');
                    $seniorCitizen->emergency_contact_relationship = Session::get('registration.step3.emergency_relationship');
                }
                
                // Add senior-specific information from step 5
                if ($request->has('senior_info')) {
                    $seniorInfo = $request->senior_info;
                    
                    // Override emergency contact if provided
                    if (!empty($seniorInfo['contact_person'])) {
                        $seniorCitizen->emergency_contact_name = $seniorInfo['contact_person'];
                    }
                    
                    if (!empty($seniorInfo['contact_number'])) {
                        $seniorCitizen->emergency_contact_number = $seniorInfo['contact_number'];
                    }
                    
                    // Add additional health information if provided
                    if (!empty($seniorInfo['additional_health'])) {
                        $seniorCitizen->health_conditions = !empty($seniorCitizen->health_conditions) 
                            ? $seniorCitizen->health_conditions . '; ' . $seniorInfo['additional_health'] 
                            : $seniorInfo['additional_health'];
                    }
                    
                    // Store pension type and living arrangement
                    if (!empty($seniorInfo['pension_type'])) {
                        $seniorCitizen->pension_type = $seniorInfo['pension_type'];
                    }
                    
                    if (!empty($seniorInfo['living_arrangement'])) {
                        $seniorCitizen->living_arrangement = $seniorInfo['living_arrangement'];
                    }
                }
                
                $seniorCitizen->save();
            }

            // Commit the transaction
            DB::commit();
            
            // Clear session data
            Session::forget('registration');
            
            return redirect()->route('admin.residents.index')
                ->with('success', "Resident registration completed successfully! Barangay ID: {$resident->barangay_id}");
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error registering resident: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resident.
     */
    public function show(Resident $resident)
    {
        // Load the household and family members separately
        $resident->load('household', 'familyMembers');
        
        return view('admin.residents.show', compact('resident'));
    }

    /**
     * Show the form for editing the specified resident.
     */
    public function edit(Resident $resident)
    {
        // Load the household and family members separately
        $resident->load('household', 'familyMembers');
        
        return view('admin.residents.edit', compact('resident'));
    }

    /**
     * Update the specified resident in storage.
     */
    public function update(Request $request, Resident $resident)
    {
        // Validate the incoming request with enhanced validation
        $validated = $request->validate([
            // Strict name validation - only letters, spaces, dots, hyphens, and apostrophes
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'suffix' => 'nullable|string|max:10',
            
            // Date validation with reasonable age limits
            'birthdate' => [
                'required', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = \Carbon\Carbon::parse($value);
                    $maxAge = 120;
                    if ($date->diffInYears(now()) > $maxAge) {
                        $fail('The birthdate cannot be more than '.$maxAge.' years ago.');
                    }
                }
            ],
            'sex' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'civil_status' => 'required|string',
            
            // Address validation with minimum length
            'address' => ['required', 'string', 'max:255', 'min:5'],
            
            // Phone number validation (11 digits)
            'contact_number' => ['required', 'string', 'regex:/^\d{11}$/'],
            
            // Enhanced email validation
            'email_address' => ['required', 'email:rfc,dns', 'max:100'],
            
            'type_of_resident' => 'required|string|max:20',
            'birthplace' => 'required|string|max:255',
            'citizenship_type' => 'required|string',
            'citizenship_country' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'philsys_id' => 'nullable|string|max:100',
            'profession_occupation' => 'nullable|string|max:100',
            
            // Numeric validation for positive numbers only
            'monthly_income' => 'nullable|numeric|min:0',
            
            'educational_attainment' => 'nullable|string',
            'education_status' => 'nullable|string',
            'mother_first_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'mother_middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'mother_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'population_sectors' => 'nullable|array',
            'population_sectors.*' => 'string',
            
            // Household validation
            'household' => 'nullable|array',
            'household.primary_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'household.primary_birthday' => [
                'nullable', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    if($value) {
                        $date = \Carbon\Carbon::parse($value);
                        $maxAge = 120;
                        if ($date->diffInYears(now()) > $maxAge) {
                            $fail('The primary birthday cannot be more than '.$maxAge.' years ago.');
                        }
                    }
                }
            ],
            'household.primary_gender' => 'nullable|string|in:Male,Female,Non-binary,Transgender,Other',
            'household.primary_phone' => ['nullable', 'string', 'regex:/^\d{11}$/'],
            'household.primary_work' => 'nullable|string|max:100',
            'household.primary_allergies' => 'nullable|string|max:255',
            'household.primary_medical_condition' => 'nullable|string|max:255',
            
            'household.secondary_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'household.secondary_birthday' => [
                'nullable', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    if($value) {
                        $date = \Carbon\Carbon::parse($value);
                        $maxAge = 120;
                        if ($date->diffInYears(now()) > $maxAge) {
                            $fail('The secondary birthday cannot be more than '.$maxAge.' years ago.');
                        }
                    }
                }
            ],
            'household.secondary_gender' => 'nullable|string|in:Male,Female,Non-binary,Transgender,Other',
            'household.secondary_phone' => ['nullable', 'string', 'regex:/^\d{11}$/'],
            'household.secondary_work' => 'nullable|string|max:100',
            'household.secondary_allergies' => 'nullable|string|max:255',
            'household.secondary_medical_condition' => 'nullable|string|max:255',
            
            'household.emergency_contact_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'household.emergency_relationship' => 'nullable|string|max:100',
            'household.emergency_work' => 'nullable|string|max:100',
            'household.emergency_phone' => ['nullable', 'string', 'regex:/^\d{11}$/'],
            
            // Family members validation
            'family_members' => 'nullable|array',
            'family_members.*.id' => 'nullable|integer|exists:family_members,id',
            'family_members.*.name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'family_members.*.relationship' => 'nullable|string|max:100',
            'family_members.*.related_to' => 'nullable|string|max:100',
            'family_members.*.gender' => 'nullable|string|in:Male,Female,Non-binary,Transgender,Other',
            'family_members.*.birthday' => [
                'nullable', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    if($value) {
                        $date = \Carbon\Carbon::parse($value);
                        $maxAge = 120;
                        if ($date->diffInYears(now()) > $maxAge) {
                            $fail('The family member birthday cannot be more than '.$maxAge.' years ago.');
                        }
                    }
                }
            ],
            'family_members.*.work' => 'nullable|string|max:100',
            'family_members.*.contact_number' => ['nullable', 'string', 'regex:/^\d{11}$/'],
            'family_members.*.allergies' => 'nullable|string|max:255',
            'family_members.*.medical_condition' => 'nullable|string|max:255',
        ], [
            // Custom validation error messages
            'first_name.regex' => 'The first name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'last_name.regex' => 'The last name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'middle_name.regex' => 'The middle name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'mother_first_name.regex' => 'The mother\'s first name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'mother_middle_name.regex' => 'The mother\'s middle name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'mother_last_name.regex' => 'The mother\'s last name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'address.min' => 'The address must be at least 5 characters.',
            'contact_number.regex' => 'The contact number must be exactly 11 digits.',
            'household.primary_name.regex' => 'The primary person name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'household.secondary_name.regex' => 'The secondary person name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'household.emergency_contact_name.regex' => 'The emergency contact name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'household.primary_phone.regex' => 'The primary phone number must be exactly 11 digits.',
            'household.secondary_phone.regex' => 'The secondary phone number must be exactly 11 digits.',
            'household.emergency_phone.regex' => 'The emergency contact phone number must be exactly 11 digits.',
            'monthly_income.min' => 'The monthly income must be a positive number.',
            'family_members.*.name.regex' => 'The family member name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'family_members.*.contact_number.regex' => 'The family member contact number must be exactly 11 digits.',
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Update resident record (excluding household and family_members)
            $residentData = collect($validated)->except(['household', 'family_members'])->toArray();
            $resident->update($residentData);

            // Handle household information
            Log::info('Handling household data', [
                'has_household_data' => $request->has('household') ? 'Yes' : 'No',
                'household_data' => $request->input('household')
            ]);
            
            // Add cross-field validation after the initial validation passes
            if ($request->has('household')) {
                // Validate cross-field requirements (e.g., if secondary_name exists, secondary_gender is required)
                if (!empty($request->input('household.secondary_name')) && empty($request->input('household.secondary_gender'))) {
                    return redirect()->back()->withErrors(['household.secondary_gender' => 'The secondary person gender is required when secondary name is provided.'])->withInput();
                }
            }

            if ($request->has('household')) {
                $householdData = $request->input('household');
                
                // Create or update the household record
                if ($resident->household) {
                    Log::info('Updating existing household', ['household_id' => $resident->household->id]);
                    $resident->household->update($householdData);
                } else {
                    Log::info('Creating new household');
                    $household = new Household($householdData);
                    $household->resident_id = $resident->id;
                    $household->address = $resident->address;
                    $household->save();
                }
            }
            
            // Handle family members
            if ($request->has('family_members') && is_array($request->family_members)) {
                $familyMembersData = $request->family_members;
                $existingIds = [];
                
                foreach ($familyMembersData as $memberData) {
                    // Skip empty entries
                    if (empty($memberData['name'])) {
                        continue;
                    }
                    
                    // Update existing family member
                    if (isset($memberData['id'])) {
                        $member = FamilyMember::find($memberData['id']);
                        if ($member && $member->resident_id == $resident->id) {
                            $member->update($memberData);
                            $existingIds[] = $member->id;
                            Log::info('Updated family member', [
                                'id' => $member->id,
                                'data' => $memberData
                            ]);
                        }
                    } 
                    // Create new family member
                    else {
                        $member = new FamilyMember($memberData);
                        $member->resident_id = $resident->id;
                        $member->household_id = $resident->household ? $resident->household->id : null;
                        $member->save();
                        $existingIds[] = $member->id;
                        Log::info('Created new family member', [
                            'id' => $member->id,
                            'data' => $memberData
                        ]);
                    }
                }
                
                // Delete family members that were removed from the form
                $resident->familyMembers()->whereNotIn('id', $existingIds)->delete();
            }
            
            // Commit the transaction
            DB::commit();
            
            Log::info('Resident update completed successfully', [
                'resident_id' => $resident->id
            ]);
            
            return redirect()->route('admin.residents.index')
                ->with('success', 'Resident "' . $resident->first_name . ' ' . $resident->last_name . '" has been updated successfully!');
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            
            Log::error('Error updating resident', [
                'resident_id' => $resident->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating resident: ' . $e->getMessage());
        }
    }

    /**
     * Archive the specified resident instead of permanently deleting.
     */
    public function destroy(Resident $resident)
    {
        try {
            // Archive (soft delete) the resident
            $resident->delete();
            
            return redirect()->route('admin.residents.index')
                ->with('success', 'Resident archived successfully! You can restore it from the archive.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error archiving resident: ' . $e->getMessage());
        }
    }

    /**
     * Display archived residents.
     */
    public function archived(Request $request)
    {
        $query = Resident::onlyTrashed();

        // Search functionality for archived residents
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('barangay_id', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('sex', $request->gender);
        }
        // Filter by civil status
        if ($request->has('civil_status') && !empty($request->civil_status)) {
            $query->where('civil_status', $request->civil_status);
        }
        // Filter by age group
        if ($request->has('age_group') && !empty($request->age_group)) {
            $now = Carbon::now();
            switch ($request->age_group) {
                case '0-17':
                    $query->whereDate('birthdate', '>', $now->copy()->subYears(18));
                    break;
                case '18-59':
                    $query->whereDate('birthdate', '<=', $now->copy()->subYears(18))
                          ->whereDate('birthdate', '>', $now->copy()->subYears(60));
                    break;
                case '60+':
                    $query->whereDate('birthdate', '<=', $now->copy()->subYears(60));
                    break;
            }
        }
        // Date filter (deleted_at)
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('deleted_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('deleted_at', '<=', $request->date_to);
        }
        $query->orderBy('deleted_at', 'desc');
        // Get all archived residents and let DataTables handle pagination
        $archivedResidents = $query->get();
        return view('admin.residents.archived', compact('archivedResidents'));
    }

    /**
     * Restore an archived resident.
     */
    public function restore($id)
    {
        try {
            $resident = Resident::onlyTrashed()->findOrFail($id);
            $resident->restore();
            
            return redirect()->route('admin.residents.archived')
                ->with('success', 'Resident restored successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error restoring resident: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete an archived resident.
     */
    public function forceDelete($id)
    {
        try {
            $resident = Resident::onlyTrashed()->findOrFail($id);
            
            // Delete related records first
            if ($resident->household) {
                $resident->household->delete();
            }
            
            $resident->familyMembers()->delete();
            
            if ($resident->seniorCitizen) {
                $resident->seniorCitizen->delete();
            }
            
            if ($resident->gad) {
                $resident->gad->delete();
            }
            
            // Permanently delete the resident
            $resident->forceDelete();
            
            return redirect()->route('admin.residents.archived')
                ->with('success', 'Resident permanently deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error permanently deleting resident: ' . $e->getMessage());
        }
    }

    /**
     * Show the senior citizens step of resident registration.
     */
    public function createStep4Senior()
    {
        // Check if previous steps have been completed
        if (!Session::has('registration.step3')) {
            return redirect()->route('admin.residents.create.step3')
                ->with('error', 'Please complete step 3 first');
        }

        // Check if this person is 60 or older to qualify as a senior
        $birthdate = Session::get('registration.step1.birthdate');
        $age = Carbon::parse($birthdate)->age;

        // If under 60, skip this step and go to family members
        if ($age < 60) {
            return redirect()->route('admin.residents.create.step4')
                ->with('info', 'Senior citizen form is only for residents aged 60 and above.');
        }

        return view('admin.residents.create.step4-senior');
    }

    /**
     * Store the senior citizens step of resident registration.
     */
    public function storeStep4Senior(Request $request)
    {
        $validated = $request->validate([
            'health_conditions' => 'nullable|string',
            'medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'receiving_pension' => 'nullable|boolean',
            'pension_type' => 'nullable|string|max:50',
            'pension_amount' => 'nullable|numeric',
            'has_philhealth' => 'nullable|boolean',
            'philhealth_number' => 'nullable|string|max:20',
            'has_senior_discount_card' => 'nullable|boolean',
            'programs_enrolled' => 'nullable|array',
            'programs_enrolled.*' => 'string',
            'last_medical_checkup' => 'nullable|date',
            'special_needs' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Store data in session
        Session::put('registration.step4-senior', $validated);

        return redirect()->route('admin.residents.create.step4');
    }

    /**
     * Show services page for a specific resident.
     */
    public function services(Resident $resident)
    {
        // Load related data
        $resident->load('household', 'familyMembers');
        
        return view('admin.residents.services', compact('resident'));
    }

    /**
     * Show census data page.
     */
    public function censusData()
    {
        // You can add logic to fetch census data here
        return view('admin.residents.census-data');
    }

    /**
     * Generate residents report.
     */
    public function reports()
    {
        $residents = Resident::with('household', 'familyMembers')
            ->whereDoesntHave('seniorCitizen')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.reports.residents', compact('residents'));
    }

    /**
     * Generate archived residents report.
     */
    public function archivedReports()
    {
        $archivedResidents = Resident::onlyTrashed()
            ->whereDoesntHave('seniorCitizen')
            ->orderBy('deleted_at', 'desc')
            ->get();
            
        return view('admin.reports.archived-residents', compact('archivedResidents'));
    }
}
