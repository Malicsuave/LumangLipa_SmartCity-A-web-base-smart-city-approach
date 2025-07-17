<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Rules\NoMaliciousContent;

class GadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Log more detailed information for debugging
        Log::info('GadRequest Authorization Check', [
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'role_id' => $user ? $user->role_id : null,
            'role_name' => $user && $user->role ? $user->role->name : null,
        ]);

        // First check if user exists
        if (!$user) {
            return false;
        }

        // Check if user has a role assigned
        if (!$user->role_id) {
            return false;
        }

        try {
            // All Barangay roles should have access to GAD features
            if ($user->role && ($user->role->name === 'Barangay Captain' || 
                               $user->role->name === 'Barangay Secretary' || 
                               $user->role->name === 'Health Worker' ||
                               $user->role->name === 'Complaint Manager')) {
                return true;
            }

            // Legacy role checks for backwards compatibility
            $allowedRoles = ['admin', 'super-admin', 'health-officer'];
            if ($user->role && in_array(strtolower($user->role->name), array_map('strtolower', $allowedRoles))) {
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error in GadRequest authorization', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Current date for age validation
        $currentDate = now();
        $maxBirthDate = $currentDate->copy()->subYears(120)->format('Y-m-d');
        
        $rules = [
            // Resident validation (only for create)
            'resident_id' => 'required|exists:residents,id',
            
            // Basic information
            'gender_identity' => [
                'required',
                'string',
                'in:Male,Female,Non-binary,Transgender,Other',
                new NoMaliciousContent()
            ],
            'gender_details' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\.\-\']+$/', // Updated to allow numbers for IDs
                Rule::requiredIf(fn() => $this->gender_identity === 'Other'),
                new NoMaliciousContent()
            ],
            'email' => [
                'nullable', 
                'email:rfc,dns',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                new NoMaliciousContent()
            ],
            'phone_number' => [
                'nullable',
                'string',
                'regex:/^(\+\d{1,3}[- ]?)?\d{10,15}$/',
                new NoMaliciousContent()
            ],
            'address' => [
                'nullable',
                'string',
                'min:5',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\.,#\-\'\/]+$/',
                new NoMaliciousContent()
            ],
            
            // Program information
            'programs_enrolled' => 'nullable|array',
            'programs_enrolled.*' => [
                'string',
                'max:100',
                new NoMaliciousContent()
            ],
            'enrollment_date' => [
                'nullable',
                'date',
                'after_or_equal:' . $maxBirthDate,
                'before_or_equal:' . $currentDate->format('Y-m-d')
            ],
            'program_end_date' => [
                'nullable',
                'date',
                'after_or_equal:enrollment_date',
            ],
            'program_status' => [
                'nullable',
                'string',
                'in:Active,Completed,On Hold,Discontinued',
                new NoMaliciousContent()
            ],
            
            // Health-related information
            'is_pregnant' => 'boolean',
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:' . $currentDate->format('Y-m-d'),
                Rule::requiredIf(fn() => $this->is_pregnant == true)
            ],
            'is_lactating' => 'nullable|boolean',
            'needs_maternity_support' => 'nullable|boolean',
            'has_philhealth' => 'nullable|boolean',
            'philhealth_number' => [
                'nullable',
                'string',
                'regex:/^[0-9]{12}$/',
                Rule::requiredIf(fn() => $this->has_philhealth == true),
                new NoMaliciousContent()
            ],
            
            // Solo parent information
            'is_solo_parent' => 'boolean',
            'solo_parent_id' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\-]+$/',
                Rule::requiredIf(fn() => $this->is_solo_parent == true),
                new NoMaliciousContent()
            ],
            'solo_parent_id_issued' => [
                'nullable',
                'date',
                'before_or_equal:' . $currentDate->format('Y-m-d'),
                Rule::requiredIf(fn() => $this->is_solo_parent == true),
            ],
            'solo_parent_id_expiry' => [
                'nullable',
                'date',
                'after:solo_parent_id_issued',
                Rule::requiredIf(fn() => $this->is_solo_parent == true),
            ],
            'solo_parent_details' => [
                'nullable',
                'string',
                'max:500',
                new NoMaliciousContent()
            ],
            
            // VAW case information
            'is_vaw_case' => 'boolean',
            'vaw_report_date' => [
                'nullable',
                'date',
                'before_or_equal:' . $currentDate->format('Y-m-d'),
                Rule::requiredIf(fn() => $this->is_vaw_case == true),
            ],
            'vaw_case_number' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\-\/]+$/',
                Rule::requiredIf(fn() => $this->is_vaw_case == true),
                new NoMaliciousContent()
            ],
            'vaw_case_status' => [
                'nullable',
                'string',
                'in:Pending,Ongoing,Resolved,Closed',
                Rule::requiredIf(fn() => $this->is_vaw_case == true),
                new NoMaliciousContent()
            ],
            'vaw_case_details' => [
                'nullable',
                'string',
                'max:1000',
                'min:10',
                Rule::requiredIf(fn() => $this->is_vaw_case == true),
                new NoMaliciousContent()
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
                new NoMaliciousContent()
            ],
        ];
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'gender_details.regex' => 'Gender details can only contain letters, numbers, spaces, dots, hyphens, and apostrophes.',
            'gender_details.required' => 'Gender details are required when selecting "Other" as gender identity.',
            'email.regex' => 'Please enter a valid email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone_number.regex' => 'Please enter a valid phone number.',
            'address.regex' => 'Address can only contain letters, numbers, spaces, commas, dots, hyphens, slashes, apostrophes and # symbol.',
            'address.min' => 'Address must be at least 5 characters.',
            'solo_parent_id.regex' => 'Solo Parent ID can only contain letters, numbers, and hyphens.',
            'solo_parent_id.required' => 'Solo Parent ID is required when indicating solo parent status.',
            'vaw_case_number.regex' => 'Case number can only contain letters, numbers, hyphens, and slashes.',
            'vaw_case_number.required' => 'Case number is required when indicating a VAW case.',
            'due_date.after_or_equal' => 'Due date must be today or a future date.',
            'due_date.required' => 'Due date is required when pregnancy is indicated.',
            'enrollment_date.before_or_equal' => 'Enrollment date cannot be in the future.',
            'enrollment_date.after_or_equal' => 'Enrollment date cannot be more than 120 years ago.',
            'solo_parent_id_issued.before_or_equal' => 'ID issuance date cannot be in the future.',
            'solo_parent_id_issued.required' => 'ID issuance date is required for solo parents.',
            'solo_parent_id_expiry.after' => 'ID expiry date must be after the issuance date.',
            'solo_parent_id_expiry.required' => 'ID expiry date is required for solo parents.',
            'vaw_report_date.required' => 'Report date is required for VAW cases.',
            'vaw_case_status.required' => 'Case status is required for VAW cases.',
            'vaw_case_details.required' => 'Case details are required for VAW cases.',
            'vaw_case_details.min' => 'Case details should contain at least 10 characters.',
            'solo_parent_details.min' => 'Solo parent details should contain at least 10 characters.',
            'philhealth_number.required' => 'PhilHealth number is required when indicating PhilHealth coverage.',
            'philhealth_number.regex' => 'PhilHealth number must be exactly 12 digits.',
        ];
    }
}
