<?php

namespace App\Http\Requests;

use App\Rules\NoMaliciousContent;
use Illuminate\Foundation\Http\FormRequest;

class AccessRequestSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && !$this->user()->role;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\.\-\']+$/',
                new NoMaliciousContent()
            ],
            'role_requested' => [
                'required',
                'string',
                'exists:roles,name',
                'in:Staff,Barangay Kagawad',
                new NoMaliciousContent()
            ],
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:1000',
                'regex:/^[a-zA-Z0-9\s\.\,\!\?\-\(\)\'\"]+$/',
                new NoMaliciousContent()
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 2 characters.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'name.regex' => 'The name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'role_requested.required' => 'Please select a role.',
            'role_requested.exists' => 'The selected role is invalid.',
            'role_requested.in' => 'You can only request Staff or Barangay Kagawad roles.',
            'reason.required' => 'Please provide a reason for your request.',
            'reason.min' => 'The reason must be at least 10 characters.',
            'reason.max' => 'The reason may not exceed 1000 characters.',
            'reason.regex' => 'The reason contains invalid characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->name ?? ''),
            'reason' => trim($this->reason ?? ''),
        ]);
    }
}