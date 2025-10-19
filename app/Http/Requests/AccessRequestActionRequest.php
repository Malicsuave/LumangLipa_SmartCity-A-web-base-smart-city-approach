<?php

namespace App\Http\Requests;

use App\Rules\NoMaliciousContent;
use Illuminate\Foundation\Http\FormRequest;

class AccessRequestActionRequest extends FormRequest
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
        $rules = [];
        
        // For approval actions
        if ($this->isMethod('post') && $this->route()->getName() === 'admin.access-requests.approve') {
            $rules = [
                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'min:3',
                    new NoMaliciousContent()
                ],
            ];
        }
        
        // For denial actions
        if ($this->isMethod('post') && $this->route()->getName() === 'admin.access-requests.deny') {
            $rules = [
                'denial_reason' => [
                    'required',
                    'string',
                    'min:10',
                    'max:1000',
                    'regex:/^[a-zA-Z0-9\s\.\,\!\?\-\(\)\'\"]+$/',
                    new NoMaliciousContent()
                ],
            ];
        }
        
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'notes.max' => 'Notes may not exceed 1000 characters.',
            'notes.min' => 'Notes must be at least 3 characters if provided.',
            'denial_reason.required' => 'A denial reason is required.',
            'denial_reason.min' => 'The denial reason must be at least 10 characters.',
            'denial_reason.max' => 'The denial reason may not exceed 1000 characters.',
            'denial_reason.regex' => 'The denial reason contains invalid characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('notes')) {
            $this->merge([
                'notes' => $this->notes ? trim($this->notes) : null,
            ]);
        }
        
        if ($this->has('denial_reason')) {
            $this->merge([
                'denial_reason' => trim($this->denial_reason ?? ''),
            ]);
        }
    }
}
