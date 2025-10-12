<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\FamilyMember;
use App\Models\Household;
use App\Models\CensusHousehold;
use App\Models\CensusMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        // Get statistics for residents only (excluding senior citizens)
        $stats = [
            'total' => Resident::count(),
            'male' => Resident::where('sex', 'Male')->count(),
            'female' => Resident::where('sex', 'Female')->count(),
            'with_id' => Resident::whereNotNull('id_issued_at')->count(),
            'pending_id' => Resident::whereNull('id_issued_at')->count(),
        ];

        return view('admin.residents', compact('residents', 'stats'));
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
        // Clear any validation errors when accessing step 1 directly
        // This prevents showing validation errors on fresh form loads
        if (request()->isMethod('get')) {
            session()->forget('errors');
        }
        
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
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    $birthdate = Carbon::parse($value);
                    $age = $birthdate->age;
                    if ($age >= 60) {
                        $fail('Residents aged 60 and above must be registered through the Senior Citizen registration form.');
                    }
                }
            ],
            'sex' => 'required|in:Male,Female,Non-binary,Transgender,Other',
            'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced',
            'citizenship_type' => 'required|in:FILIPINO,DUAL,NATURALIZED,FOREIGN',
            'citizenship_country' => 'nullable|string|max:100',
            'educational_attainment' => 'required|string',
            'education_status' => 'required|string',
            'religion' => 'nullable|string|max:100',
            'profession_occupation' => 'nullable|string|max:100',
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
            'civil_status.in' => 'Please select a valid civil status.',
            'citizenship_type.required' => 'Please select a citizenship type.',
            'citizenship_type.in' => 'Please select a valid citizenship type.',
            'educational_attainment.required' => 'Please select your educational attainment.',
            'education_status.required' => 'Please select your education status.',
        ]);

        // Additional validation for citizenship country
        if (in_array($request->citizenship_type, ['DUAL', 'FOREIGN', 'NATURALIZED'])) {
            $request->validate([
                'citizenship_country' => 'required|string|max:100'
            ], [
                'citizenship_country.required' => 'Country is required for the selected citizenship type.'
            ]);
        }

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
            'purok' => 'required|string|max:100',
            'custom_purok' => 'required_if:purok,Other|nullable|string|max:100',
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
            'purok.required' => 'Please select a purok.',
            'custom_purok.required_if' => 'Please specify the purok/sitio name.',
            'emergency_contact_name.required' => 'The emergency contact name is required.',
            'emergency_contact_relationship.required' => 'Please select the relationship to emergency contact.',
            'emergency_contact_number.required' => 'The emergency contact number is required.',
            'emergency_contact_number.numeric' => 'The emergency contact number must contain only numbers.',
            'emergency_contact_number.digits' => 'The emergency contact number must be exactly 11 digits.',
        ]);

        // Handle custom purok
        if ($validated['purok'] === 'Other') {
            $validated['purok'] = $validated['custom_purok'];
        }
        unset($validated['custom_purok']);

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
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
            'signature' => 'nullable|image|mimes:jpeg,jpg,png|max:1024', // 1MB max
        ], [
            'photo.image' => 'The photo must be an image file.',
            'photo.mimes' => 'The photo must be a JPEG, JPG, or PNG file.',
            'photo.max' => 'The photo must not exceed 2MB in size.',
            'signature.image' => 'The signature must be an image file.',
            'signature.mimes' => 'The signature must be a JPEG, JPG, or PNG file.',
            'signature.max' => 'The signature must not exceed 1MB in size.',
        ]);

        // Handle file uploads - preserve existing files if no new ones uploaded
        $photoPath = Session::get('registration.step3.photo');
        $signaturePath = Session::get('registration.step3.signature');

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($photoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('residents/photos', 'public');
        }

        if ($request->hasFile('signature')) {
            // Delete old signature if exists
            if ($signaturePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($signaturePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($signaturePath);
            }
            $signaturePath = $request->file('signature')->store('residents/signatures', 'public');
        }

        // Store data in session (preserve existing files if no new uploads)
        Session::put('registration.step3', [
            'photo' => $photoPath,
            'signature' => $signaturePath,
        ]);

        return redirect()->route('admin.residents.create.review');
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
        return view('admin.residents.registration.step4');
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
        if (!Session::has('registration.step3')) {
            return redirect()->route('admin.residents.create.step3')
                ->with('error', 'Please complete step 3 first');
        }

        return view('admin.residents.registration.step4');
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
            $resident->current_address = Session::get('registration.step2.current_address'); // Fixed: use current_address column
            $resident->purok = Session::get('registration.step2.purok');
            
            // Additional fields from Step 1
            $resident->citizenship_type = Session::get('registration.step1.citizenship_type');
            $resident->citizenship_country = Session::get('registration.step1.citizenship_country');
            $resident->profession_occupation = Session::get('registration.step1.profession_occupation');
            $resident->religion = Session::get('registration.step1.religion');
            $resident->educational_attainment = Session::get('registration.step1.educational_attainment');
            $resident->education_status = Session::get('registration.step1.education_status');
            
            // Emergency contact info from Step 2
            $resident->emergency_contact_name = Session::get('registration.step2.emergency_contact_name');
            $resident->emergency_contact_relationship = Session::get('registration.step2.emergency_contact_relationship');
            $resident->emergency_contact_number = Session::get('registration.step2.emergency_contact_number');
            
            // Generate a unique barangay ID using the model's method
            $resident->barangay_id = Resident::generateBarangayId();
            
            // Photo and signature (Step 3)
            $photoPath = Session::get('registration.step3.photo');
            $signaturePath = Session::get('registration.step3.signature');
            
            // Extract just the filename from the full storage path
            $resident->photo = $photoPath ? basename($photoPath) : null;
            $resident->signature = $signaturePath ? basename($signaturePath) : null;
            
            // Set ID status (dates will be set by model events if needed)
            $resident->id_status = 'issued';
            
            // Save the resident
            $resident->save();

            // Create household record (only if step3 contains actual household data)
            if (Session::has('registration.step3.primary_name')) {
                $household = new Household();
                $household->address = $resident->current_address;
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
                // Create independent senior citizen record with all resident data
                $seniorCitizen = \App\Models\SeniorCitizen::create([
                    // Copy resident data to senior citizen (since they're now independent)
                    'type_of_resident' => $resident->type_of_resident,
                    'first_name' => $resident->first_name,
                    'middle_name' => $resident->middle_name,
                    'last_name' => $resident->last_name,
                    'suffix' => $resident->suffix,
                    'birthdate' => $resident->birthdate,
                    'birthplace' => $resident->birthplace,
                    'sex' => $resident->sex,
                    'civil_status' => $resident->civil_status,
                    'citizenship_type' => $resident->citizenship_type,
                    'citizenship_country' => $resident->citizenship_country,
                    'educational_attainment' => $resident->educational_attainment,
                    'religion' => $resident->religion,
                    'profession_occupation' => $resident->profession_occupation,
                    'contact_number' => $resident->contact_number,
                    'email_address' => $resident->email_address,
                    'current_address' => $resident->current_address,
                    'photo' => $resident->photo,
                    'signature' => $resident->signature,
                    'senior_id_number' => \App\Models\SeniorCitizen::generateSeniorIdNumber(),
                    'senior_id_issued_at' => Carbon::now(),
                    'senior_id_expires_at' => Carbon::now()->addYears(5),
                    'senior_id_status' => 'issued',
                ]);
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
            
            // Issue ID and send email notification if email address is provided
            if (!empty($resident->email_address)) {
                try {
                    // Update ID status and dates
                    $issuedAt = Carbon::now();
                    $expiresAt = $issuedAt->copy()->addYears(5);
                    
                    $resident->update([
                        'id_status' => 'issued',
                        'id_issued_at' => $issuedAt,
                        'id_expires_at' => $expiresAt,
                    ]);
                    
                    // Generate QR code data
                    $qrData = json_encode([
                        'id' => $resident->barangay_id,
                        'name' => $resident->full_name,
                        'dob' => $resident->birthdate ? Carbon::parse($resident->birthdate)->format('Y-m-d') : null,
                    ]);
                    
                    $qrCode = \App\Facades\QrCode::generateQrCode($qrData, 300);
                    
                    // If the QR code already has the data URI prefix, extract just the base64 part
                    if (strpos($qrCode, 'data:image/png;base64,') === 0) {
                        $qrCode = substr($qrCode, 22); // Remove the prefix
                    }
                    
                    // Generate PDF
                    $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.residents.id-pdf', [
                        'resident' => $resident,
                        'qrCode' => $qrCode,
                    ]);
                    
                    // Set PDF options for optimal design preservation
                    $pdf->setOptions([
                        'page-width' => '148mm',
                        'page-height' => '180mm',
                        'orientation' => 'Portrait',
                        'margin-top' => '5mm',
                        'margin-right' => '5mm',
                        'margin-bottom' => '5mm',
                        'margin-left' => '5mm',
                        'encoding' => 'UTF-8',
                        'enable-local-file-access' => true,
                        'disable-smart-shrinking' => true,
                        'print-media-type' => true,
                        'no-outline' => true,
                        'disable-external-links' => true,
                        'disable-internal-links' => true,
                        'disable-javascript' => true,
                        'no-images' => false,
                        'dpi' => 300,
                        'image-quality' => 100,
                        'zoom' => 1.0,
                        'viewport-size' => '1280x1024',
                        'javascript-delay' => 0,
                        'load-error-handling' => 'ignore',
                        'load-media-error-handling' => 'ignore'
                    ]);
                    
                    // Create temporary file for PDF
                    $tempPath = storage_path('app/temp');
                    if (!file_exists($tempPath)) {
                        mkdir($tempPath, 0755, true);
                    }
                    
                    $pdfFileName = 'resident_id_' . $resident->id . '_' . time() . '.pdf';
                    $pdfPath = $tempPath . '/' . $pdfFileName;
                    
                    // Save PDF to temp directory
                    $pdf->save($pdfPath);
                    
                    // Send notification with PDF attached
                    $resident->notify(new \App\Notifications\ResidentIdIssued($resident, $pdfPath));
                    
                    Log::info("Email notification sent to {$resident->email_address} for resident ID: {$resident->barangay_id}");
                    
                    // Clean up temp file after a delay
                    \Illuminate\Support\Facades\Queue::later(now()->addMinutes(5), function () use ($pdfPath) {
                        if (file_exists($pdfPath)) {
                            unlink($pdfPath);
                        }
                    });
                    
                } catch (\Exception $e) {
                    // Log error but don't fail the registration
                    Log::error("Failed to send email notification for resident {$resident->barangay_id}: " . $e->getMessage());
                }
            }
            
            // Clear session data
            Session::forget('registration');
            
            return redirect()->route('admin.residents.index')
                ->with('success', "Resident registration completed successfully! Barangay ID: {$resident->barangay_id}" . 
                    (!empty($resident->email_address) ? " An email with the resident ID has been sent to {$resident->email_address}." : ""));
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
        $households = CensusHousehold::with(['members'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get statistics for census data
        $stats = [
            'total_households' => CensusHousehold::count(),
            'total_population' => CensusMember::count(),
            'owned_houses' => CensusHousehold::where('housing_type', 'Owned House')->count(),
            'rented_houses' => CensusHousehold::where('housing_type', 'Rented House')->count(),
            'apartments' => CensusHousehold::where('housing_type', 'Apartment')->count(),
        ];

        return view('admin.residents.census-data', compact('households', 'stats'));
    }

    /**
     * Store new census record (household).
     */
    public function storeCensusRecord(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'primary_name' => 'required|string|max:255',
            'primary_birthday' => 'required|date',
            'primary_gender' => 'required|in:Male,Female',
            'primary_phone' => 'nullable|string|max:20',
            'primary_work' => 'nullable|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_relationship' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
        ]);

        try {
            $household = Household::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Census record created successfully!',
                'data' => $household->load('resident')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create census record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get census record for editing.
     */
    public function editCensusRecord(Household $household)
    {
        try {
            return response()->json($household);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch census record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update census record (household).
     */
    public function updateCensusRecord(Request $request, Household $household)
    {
        $request->validate([
            'primary_name' => 'required|string|max:255',
            'primary_birthday' => 'required|date',
            'primary_gender' => 'required|in:Male,Female',
            'primary_phone' => 'nullable|string|max:20',
            'primary_work' => 'nullable|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_relationship' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
        ]);

        try {
            $household->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Census record updated successfully!',
                'data' => $household->load('resident')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update census record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete census record (household).
     */
    public function destroyCensusRecord(Household $household)
    {
        try {
            $household->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Census record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete census record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate residents report.
     */
    public function reports()
    {
        $residents = Resident::with('household', 'familyMembers')
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
            ->orderBy('deleted_at', 'desc')
            ->get();
            
        return view('admin.reports.archived-residents', compact('archivedResidents'));
    }

    /**
     * Show the form for creating a new census record.
     */
    public function createCensus()
    {
        return view('admin.residents.census.create');
    }

    /**
     * Store a newly created census record.
     */
    public function storeCensus(Request $request)
    {
        try {
            $validated = $request->validate([
                'primary_name' => 'required|string|max:255',
                'primary_birthday' => 'required|date|before_or_equal:today',
                'primary_gender' => 'required|string',
                'primary_phone' => 'nullable|string|max:20',
                'primary_work' => 'nullable|string|max:255',
                'primary_allergies' => 'nullable|string|max:255',
                'primary_medical_condition' => 'nullable|string|max:1000',
                'secondary_name' => 'nullable|string|max:255',
                'secondary_birthday' => 'nullable|date|before_or_equal:today',
                'secondary_gender' => 'nullable|string',
                'secondary_phone' => 'nullable|string|max:20',
                'secondary_work' => 'nullable|string|max:255',
                'secondary_allergies' => 'nullable|string|max:255',
                'secondary_medical_condition' => 'nullable|string|max:1000',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_relationship' => 'nullable|string|max:100',
                'emergency_work' => 'nullable|string|max:255',
                'emergency_phone' => 'nullable|string|max:20',
            ], [
                'primary_name.required' => 'Primary person name is required.',
                'primary_birthday.required' => 'Primary person birthday is required.',
                'primary_birthday.before_or_equal' => 'Birthday cannot be in the future.',
                'primary_gender.required' => 'Primary person gender is required.',
            ]);

            // Create the household record
            $household = Household::create($validated);

            return redirect()->route('admin.residents.census-data')
                ->with('success', 'Household census record created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please correct the validation errors and try again.');

        } catch (\Exception $e) {
            Log::error('Error creating household census record: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create household census record. Please try again.');
        }
    }

    // Multi-step census registration methods
    
    /**
     * Show Step 1 of census registration (Household Information)
     */
    public function censusStep1()
    {
        return view('admin.residents.census.step1');
    }

    /**
     * Store Step 1 census data and redirect to Step 2
     */
    public function storeCensusStep1(Request $request)
    {
        $request->validate([
            'head_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'contact_number' => 'nullable|string|max:20',
            'housing_type' => 'required|string|in:Concrete,Semi-concrete,Wood,Bamboo,Mixed Materials,Makeshift,Apartment/Condominium,Other',
        ]);

        // Store step 1 data in session
        Session::put('census.step1', [
            'head_name' => $request->head_name,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'housing_type' => $request->housing_type,
        ]);

        return redirect()->route('admin.residents.census.step2');
    }

    /**
     * Show Step 2 of census registration (Household Members)
     */
    public function censusStep2()
    {
        // Check if step 1 data exists
        if (!Session::has('census.step1')) {
            return redirect()->route('admin.residents.census.step1')
                ->with('error', 'Please complete Step 1 first.');
        }

        return view('admin.residents.census.step2');
    }

    /**
     * Store Step 2 census data and redirect to Step 3
     */
    public function storeCensusStep2(Request $request)
    {
        // Check if step 1 data exists
        if (!Session::has('census.step1')) {
            return redirect()->route('admin.residents.census.step1')
                ->with('error', 'Please complete Step 1 first.');
        }

        $request->validate([
            'members' => 'required|array|min:1',
            'members.*.fullname' => 'required|string|max:255',
            'members.*.relationship_to_head' => 'required|string|max:100',
            'members.*.dob' => 'required|date|before:today',
            'members.*.gender' => 'required|string|in:Male,Female',
            'members.*.civil_status' => 'required|string|in:Single,Married,Widowed,Separated,Divorced',
            'members.*.education' => 'nullable|string|max:100',
            'members.*.occupation' => 'nullable|string|max:100',
            'members.*.category' => 'nullable|string|max:100',
        ]);

        // Store step 2 data in session
        Session::put('census.step2', [
            'members' => $request->members,
        ]);

        return redirect()->route('admin.residents.census.step3');
    }

    /**
     * Show Step 3 of census registration (Review & Submit)
     */
    public function censusStep3()
    {
        // Check if previous steps data exists
        if (!Session::has('census.step1') || !Session::has('census.step2')) {
            return redirect()->route('admin.residents.census.step1')
                ->with('error', 'Please complete all previous steps first.');
        }

        return view('admin.residents.census.step3');
    }

    /**
     * Store final census data to database
     */
    public function storeCensusStep3(Request $request)
    {
        // Check if previous steps data exists
        if (!Session::has('census.step1') || !Session::has('census.step2')) {
            return redirect()->route('admin.residents.census.step1')
                ->with('error', 'Please complete all previous steps first.');
        }

        try {
            DB::beginTransaction();

            $step1Data = Session::get('census.step1');
            $step2Data = Session::get('census.step2');

            // Create the household record
            $household = \App\Models\CensusHousehold::create([
                'head_name' => $step1Data['head_name'],
                'address' => $step1Data['address'],
                'contact_number' => $step1Data['contact_number'],
                'housing_type' => $step1Data['housing_type'],
            ]);

            // Create member records
            foreach ($step2Data['members'] as $memberData) {
                \App\Models\CensusMember::create([
                    'household_id' => $household->household_id,
                    'fullname' => $memberData['fullname'],
                    'relationship_to_head' => $memberData['relationship_to_head'],
                    'dob' => $memberData['dob'],
                    'gender' => $memberData['gender'],
                    'civil_status' => $memberData['civil_status'],
                    'education' => $memberData['education'] ?? null,
                    'occupation' => $memberData['occupation'] ?? null,
                    'category' => $memberData['category'] ?? null,
                ]);
            }

            DB::commit();

            // Clear session data
            Session::forget('census');

            return redirect()->route('admin.residents.census-data')
                ->with('success', 'Census record has been successfully created with ' . count($step2Data['members']) . ' household members.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating multi-step census record: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to create census record. Please try again.');
        }
    }

    /**
     * Generate PDF for resident ID card
     */
    private function generateResidentIdPdf(Resident $resident)
    {
        // For now, return a placeholder path
        // You can implement actual PDF generation using libraries like DomPDF or TCPDF
        // This is a simple implementation that creates a basic text file as placeholder
        
        $fileName = 'ResidentID_' . $resident->barangay_id . '.txt';
        $filePath = storage_path('app/public/resident_ids/' . $fileName);
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        // Create simple text file with resident information
        $content = "BARANGAY LUMANGLIPA RESIDENT ID\n";
        $content .= "==============================\n\n";
        $content .= "ID Number: " . $resident->barangay_id . "\n";
        $content .= "Name: " . $resident->first_name . " " . ($resident->middle_name ? $resident->middle_name . " " : "") . $resident->last_name . ($resident->suffix ? " " . $resident->suffix : "") . "\n";
        $content .= "Address: " . $resident->address . "\n";
        $content .= "Contact: " . $resident->contact_number . "\n";
        $content .= "Birth Date: " . Carbon::parse($resident->birthdate)->format('F d, Y') . "\n";
        $content .= "Gender: " . $resident->sex . "\n";
        $content .= "Civil Status: " . $resident->civil_status . "\n";
        $content .= "\nIssued: " . now()->format('F d, Y') . "\n";
        $content .= "Valid until: " . now()->addYears(5)->format('F d, Y') . "\n";
        
        file_put_contents($filePath, $content);
        
        return $filePath;
    }

    /**
     * Remove uploaded file from session and storage during registration.
     */
    public function removeUploadedFile(Request $request)
    {
        $field = $request->input('field'); // 'photo' or 'signature'
        
        if (!in_array($field, ['photo', 'signature'])) {
            return response()->json(['success' => false, 'message' => 'Invalid field']);
        }
        
        // Get the file path from session
        $filePath = Session::get("registration.step3.{$field}");
        
        if ($filePath) {
            // Delete the file from storage
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
            
            // Remove from session
            Session::forget("registration.step3.{$field}");
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Get all residents for API consumption
     */
    public function getAllResidentsApi()
    {
        $residents = Resident::select([
            'id',
            'first_name',
            'middle_name', 
            'last_name',
            'birthdate',
            'sex as gender',
            'civil_status',
            'current_address as address',
            'contact_number',
            'educational_attainment',
            'profession_occupation',
            'citizenship_type as citizenship',
            'religion'
        ])
        ->get()
        ->map(function ($resident) {
            // Calculate age from birthdate
            $age = $resident->birthdate ? Carbon::parse($resident->birthdate)->age : null;
            
            return [
                'id' => $resident->id,
                'full_name' => trim($resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name),
                'age' => $age,
                'gender' => $resident->gender,
                'address' => $resident->address,
                'contact_number' => $resident->contact_number,
                'birthdate' => $resident->birthdate,
                'civil_status' => $resident->civil_status,
                'educational_attainment' => $resident->educational_attainment,
                'profession_occupation' => $resident->profession_occupation,
                'citizenship' => $resident->citizenship,
                'religion' => $resident->religion,
            ];
        });
        
        return response()->json($residents);
    }
}
