<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\FamilyMember;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
                    ->orWhere('contact_number', 'like', "%{$search}%");
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

        $residents = $query->paginate(10);

        return view('admin.residents', compact('residents'));
    }

    /**
     * Show the form for creating a new resident (Step 1).
     */
    public function create()
    {
        // Clear any previous session data
        Session::forget('registration');
        
        return view('admin.residents.create.step1');
    }

    /**
     * Show the first step of resident registration.
     */
    public function createStep1()
    {
        // If navigating back to step 1, we preserve existing session data
        // This allows users to edit step 1 without losing data from other steps
        return view('admin.residents.create.step1');
    }

    /**
     * Store the first step of resident registration.
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'type_of_resident' => 'required|in:Non-Migrant,Migrant,Transient',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|in:Single,Married,Widowed,Separated,Divorced'
        ]);

        // Store data in session
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
        return view('admin.residents.create.step2');
    }

    /**
     * Store the second step of resident registration.
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            // Match field names with form inputs in step2.blade.php
            'citizenship_type' => 'required|string|in:FILIPINO,Dual Citizen,Foreigner',
            'citizenship_country' => 'nullable|required_if:citizenship_type,Dual Citizen,Foreigner|string|max:100',
            'profession_occupation' => 'required|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            'contact_number' => 'required|numeric|digits:11',
            'email_address' => 'required|email|max:255',
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'required|string|max:100',
            'education_status' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'philsys_id' => 'nullable|string|max:100',
            'population_sectors' => 'nullable|array',
            'population_sectors.*' => 'string',
            'mother_first_name' => 'nullable|string|max:100',
            'mother_middle_name' => 'nullable|string|max:100',
            'mother_last_name' => 'nullable|string|max:100',
        ], 
        [
            'citizenship_type.required' => 'Please select your citizenship type.',
            'citizenship_country.required_if' => 'Please specify the country for dual citizenship or foreign citizenship.',
            'educational_attainment.required' => 'Please specify your highest educational attainment.',
            'address.required' => 'Please provide your current address.',
            'contact_number.required' => 'Please provide a contact number.',
            'contact_number.regex' => 'The contact number must be a valid Philippine mobile number (e.g. 09XXXXXXXXX or +639XXXXXXXXX).',
            'monthly_income.numeric' => 'Monthly income must be a valid number.',
            'monthly_income.min' => 'Monthly income cannot be negative.',
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
        return view('admin.residents.create.step3');
    }

    /**
     * Store the third step of resident registration.
     */
    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'primary_name' => 'required|string|max:255',
            'primary_birthday' => 'required|date',
            'primary_gender' => 'required|in:Male,Female',
            'primary_phone' => 'required|numeric|digits:11',
            'primary_work' => 'nullable|string|max:100',
            'primary_allergies' => 'nullable|string|max:255',
            'primary_medical_condition' => 'nullable|string|max:255',
            
            'secondary_name' => 'nullable|string|max:255',
            'secondary_birthday' => 'nullable|date',
            'secondary_gender' => 'nullable|in:Male,Female',
            'secondary_phone' => 'nullable|numeric|digits:11',
            'secondary_work' => 'nullable|string|max:100',
            'secondary_allergies' => 'nullable|string|max:255',
            'secondary_medical_condition' => 'nullable|string|max:255',
            
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_work' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|numeric|digits:11',
        ], [
            'primary_phone.digits' => 'The primary phone number must be exactly 11 digits.',
            'secondary_phone.digits' => 'The secondary phone number must be exactly 11 digits.',
            'emergency_phone.digits' => 'The emergency contact phone number must be exactly 11 digits.'
        ]);

        // Store data in session
        Session::put('registration.step3', $validated);

        return redirect()->route('admin.residents.create.step4');
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
            'family_members.*.birthday' => 'nullable|date',
            'family_members.*.gender' => 'nullable|in:Male,Female',
            'family_members.*.relationship' => 'nullable|string|max:100',
            'family_members.*.related_to' => 'nullable|in:primary,secondary,both',
            'family_members.*.phone' => 'nullable|numeric|digits:11',
            'family_members.*.work' => 'nullable|string|max:100',
            'family_members.*.allergies' => 'nullable|string|max:255',
            'family_members.*.medical_condition' => 'nullable|string|max:255',
        ], [
            'family_members.*.phone.digits' => 'Each family member phone number must be exactly 11 digits.',
            'family_members.*.related_to.in' => 'The family member relation must be either primary, secondary, or both.',
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

        // Check if all session data exists
        if (!Session::has('registration.step1') || 
            !Session::has('registration.step2') || 
            !Session::has('registration.step3')) {
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
            
            // Citizenship & Education (Step 2)
            $resident->citizenship_type = Session::get('registration.step2.citizenship_type');
            $resident->citizenship_country = Session::get('registration.step2.citizenship_country');
            $resident->profession_occupation = Session::get('registration.step2.profession_occupation');
            $resident->monthly_income = Session::get('registration.step2.monthly_income');
            $resident->contact_number = Session::get('registration.step2.contact_number');
            $resident->email_address = Session::get('registration.step2.email_address');
            $resident->religion = Session::get('registration.step2.religion');
            $resident->educational_attainment = Session::get('registration.step2.educational_attainment');
            $resident->education_status = Session::get('registration.step2.education_status');
            $resident->address = Session::get('registration.step2.address');
            $resident->philsys_id = Session::get('registration.step2.philsys_id');
            
            if (Session::has('registration.step2.population_sectors')) {
                $resident->population_sectors = Session::get('registration.step2.population_sectors');
            }
            
            // Mother's maiden name if provided
            if (Session::get('registration.step2.mother_first_name') || 
                Session::get('registration.step2.mother_middle_name') || 
                Session::get('registration.step2.mother_last_name')) {
                
                $resident->mother_first_name = Session::get('registration.step2.mother_first_name');
                $resident->mother_middle_name = Session::get('registration.step2.mother_middle_name');
                $resident->mother_last_name = Session::get('registration.step2.mother_last_name');
            }
            
            // Generate a unique barangay ID using the model's method
            $resident->barangay_id = Resident::generateBarangayId();
            
            // Save the resident
            $resident->save();

            // Create household record
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

            // Create family members if any
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

            // Commit the transaction
            DB::commit();
            
            // Clear session data
            Session::forget('registration');
            
            return redirect()->route('admin.residents.index')
                ->with('success', 'Resident registered successfully!');
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
        // Validate the incoming request
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'birthdate' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|string',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email_address' => 'required|email|max:100',
            // Add more validation rules as needed
            'population_sectors' => 'nullable|array',
            'population_sectors.*' => 'string',
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Update resident record
            $resident->update($validated);

            // Update population_sectors if present
            if ($request->has('population_sectors')) {
                $resident->population_sectors = $request->input('population_sectors');
                $resident->save();
            }
            
            // Update household if needed
            if ($request->has('household')) {
                $resident->household->update($request->household);
            }
            
            // Update family members if needed
            // This would require more complex handling based on your form structure
            
            // Commit the transaction
            DB::commit();
            
            return redirect()->route('admin.residents.show', $resident->id)
                ->with('success', 'Resident updated successfully!');
        } catch (\Exception $e) {
            // Roll back the transaction in case of error
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error updating resident: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resident from storage.
     */
    public function destroy(Resident $resident)
    {
        try {
            // Delete the resident
            // This should cascade to related records if set up properly in the database
            $resident->delete();
            
            return redirect()->route('admin.residents.index')
                ->with('success', 'Resident deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting resident: ' . $e->getMessage());
        }
    }
}
