<?php

namespace App\Http\Requests;

use App\Rules\NoMaliciousContent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminApprovalUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role && in_array($this->user()->role->name, ['Barangay Captain', 'Barangay Secretary']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $approvalId = $this->route('approval')->id ?? null;
        
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'lowercase',
                Rule::unique('admin_approvals', 'email')->ignore($approvalId),
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                new NoMaliciousContent()
            ],
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
                'min:1'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
                'min:3',
                new NoMaliciousContent()
            ],
            'is_active' => [
                'required',
                'boolean'
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already used by another approval.',
            'email.lowercase' => 'Email address must be lowercase.',
            'email.regex' => 'Please enter a valid email format.',
            'role_id.required' => 'Please select a role.',
            'role_id.exists' => 'The selected role is invalid.',
            'notes.max' => 'Notes may not exceed 1000 characters.',
            'notes.min' => 'Notes must be at least 3 characters if provided.',
            'is_active.required' => 'Please specify if this approval is active.',
            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email ?? '')),
            'notes' => $this->notes ? trim($this->notes) : null,
            'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
