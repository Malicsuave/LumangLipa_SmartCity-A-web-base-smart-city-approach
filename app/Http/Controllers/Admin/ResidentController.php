namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ResidentController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resident $resident)
    {
        // Enhanced backend validation
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'suffix' => ['nullable', 'string', 'max:10', 'regex:/^[a-zA-Z\s\.\-\']*$/'],
            'email_address' => [
                'required', 
                'email:rfc,dns', 
                Rule::unique('residents')->ignore($resident->id),
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'contact_number' => ['required', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            'sex' => ['required', 'string', 'in:Male,Female'],
            'civil_status' => ['required', 'string', 'in:Single,Married,Widowed,Separated,Divorced'],
            'birthdate' => [
                'required', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value);
                    $minDate = Carbon::now()->subYears(120);
                    
                    if ($date->isBefore($minDate)) {
                        $fail('The birthdate is too far in the past. Maximum age is 120 years.');
                    }
                }
            ],
            'birthplace' => ['required', 'string', 'min:2', 'max:100'],
            'address' => ['required', 'string', 'min:5', 'max:255'],
            'type_of_resident' => ['required', 'string', 'in:Non-Migrant,Migrant,Transient'],
            'citizenship_type' => ['required', 'string'],
            'citizenship_country' => ['nullable', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'philsys_id' => ['nullable', 'string', 'max:20'],
            'profession_occupation' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'educational_attainment' => ['nullable', 'string'],
            'education_status' => ['nullable', 'string'],
            
            // Household information (all fields nullable)
            'household.primary_name' => ['nullable', 'string', 'max:200'],
            'household.primary_birthday' => [
                'nullable', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $date = Carbon::parse($value);
                        $minDate = Carbon::now()->subYears(120);
                        
                        if ($date->isBefore($minDate)) {
                            $fail('The birthdate is too far in the past. Maximum age is 120 years.');
                        }
                    }
                }
            ],
            'household.primary_gender' => ['nullable', 'string', 'in:Male,Female'],
            'household.primary_phone' => ['nullable', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            'household.primary_work' => ['nullable', 'string', 'max:100'],
            'household.primary_allergies' => ['nullable', 'string', 'max:255'],
            'household.primary_medical_condition' => ['nullable', 'string', 'max:255'],
            
            'household.secondary_name' => ['nullable', 'string', 'max:200'],
            'household.secondary_birthday' => [
                'nullable', 
                'date', 
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $date = Carbon::parse($value);
                        $minDate = Carbon::now()->subYears(120);
                        
                        if ($date->isBefore($minDate)) {
                            $fail('The birthdate is too far in the past. Maximum age is 120 years.');
                        }
                    }
                }
            ],
            'household.secondary_gender' => ['nullable', 'string', 'in:Male,Female'],
            'household.secondary_phone' => ['nullable', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            'household.secondary_work' => ['nullable', 'string', 'max:100'],
            'household.secondary_allergies' => ['nullable', 'string', 'max:255'],
            'household.secondary_medical_condition' => ['nullable', 'string', 'max:255'],
            
            'household.emergency_contact_name' => ['nullable', 'string', 'max:200'],
            'household.emergency_relationship' => ['nullable', 'string', 'max:100'],
            'household.emergency_work' => ['nullable', 'string', 'max:100'],
            'household.emergency_phone' => ['nullable', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            
            // Family member validation
            'family_members' => ['nullable', 'array'],
            'family_members.*.id' => ['nullable', 'numeric', 'exists:family_members,id'],
            'family_members.*.name' => ['required', 'string', 'max:200', 'regex:/^[a-zA-Z\s\.\-\']+$/'],
            'family_members.*.relationship' => ['required', 'string', 'max:100'],
            'family_members.*.related_to' => ['nullable', 'string', 'max:100'],
            'family_members.*.gender' => ['required', 'string', 'in:Male,Female'],
            'family_members.*.birthday' => [
                'nullable', 
                'date', 
                'before_or_equal:today', 
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $date = Carbon::parse($value);
                        $minDate = Carbon::now()->subYears(120);
                        
                        if ($date->isBefore($minDate)) {
                            $fail('The birthdate is too far in the past. Maximum age is 120 years.');
                        }
                    }
                }
            ],
            'family_members.*.work' => ['nullable', 'string', 'max:100'],
            'family_members.*.contact_number' => ['nullable', 'string', 'size:11', 'regex:/^[0-9]+$/'],
            'family_members.*.allergies' => ['nullable', 'string', 'max:255'],
            'family_members.*.medical_condition' => ['nullable', 'string', 'max:255'],
        ]);

        // Cross-field validation logic
        if (!empty($request->input('household.secondary_name')) && empty($request->input('household.secondary_gender'))) {
            return redirect()->back()->withInput()->withErrors(['household.secondary_gender' => 'Gender is required when secondary person is provided.']);
        }

        try {
            DB::beginTransaction();
            
            // Update resident information
            $resident->update([
                'first_name' => $validatedData['first_name'],
                'middle_name' => $validatedData['middle_name'] ?? null,
                'last_name' => $validatedData['last_name'],
                'suffix' => $validatedData['suffix'] ?? null,
                'email_address' => $validatedData['email_address'],
                'contact_number' => $validatedData['contact_number'],
                'sex' => $validatedData['sex'],
                'civil_status' => $validatedData['civil_status'],
                'birthdate' => $validatedData['birthdate'],
                'birthplace' => $validatedData['birthplace'],
                'address' => $validatedData['address'],
                'type_of_resident' => $validatedData['type_of_resident'],
                'citizenship_type' => $validatedData['citizenship_type'],
                'citizenship_country' => $validatedData['citizenship_country'] ?? null,
                'religion' => $validatedData['religion'] ?? null,
                'philsys_id' => $validatedData['philsys_id'] ?? null,
                'profession_occupation' => $validatedData['profession_occupation'] ?? null,
                'monthly_income' => $validatedData['monthly_income'] ?? null,
                'educational_attainment' => $validatedData['educational_attainment'] ?? null,
                'education_status' => $validatedData['education_status'] ?? null,
            ]);
            
            // Update or create household information
            if ($request->has('household')) {
                // Update or create household record
                $householdData = $request->input('household');
                
                if ($resident->household) {
                    // Update existing household record
                    $resident->household->update($householdData);
                } else {
                    // Create new household record for this resident
                    $household = new Household($householdData);
                    $resident->household()->save($household);
                }
            }
            
            // Update family members
            if ($request->has('family_members')) {
                $familyMembers = $request->input('family_members');
                $currentFamilyMemberIds = [];
                
                foreach ($familyMembers as $memberData) {
                    // Skip empty rows
                    if (empty($memberData['name'])) {
                        continue;
                    }
                    
                    if (isset($memberData['id'])) {
                        // Update existing family member
                        $familyMember = FamilyMember::find($memberData['id']);
                        if ($familyMember) {
                            $familyMember->update($memberData);
                            $currentFamilyMemberIds[] = $familyMember->id;
                        }
                    } else {
                        // Create new family member
                        $familyMember = new FamilyMember($memberData);
                        $resident->familyMembers()->save($familyMember);
                        $currentFamilyMemberIds[] = $familyMember->id;
                    }
                }
                
                // Remove family members not in the list
                $resident->familyMembers()->whereNotIn('id', $currentFamilyMemberIds)->delete();
            } else {
                // No family members provided, remove all existing ones
                $resident->familyMembers()->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.residents.show', $resident)->with('success', 'Resident information updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update resident: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['general' => 'Failed to update resident information. Please try again.']);
        }
    }
}