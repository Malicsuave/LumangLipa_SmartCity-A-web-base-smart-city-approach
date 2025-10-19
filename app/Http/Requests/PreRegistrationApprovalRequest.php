<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoMaliciousContent;

class PreRegistrationApprovalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-pre-registrations');
    }

    public function rules()
    {
        return [
            'action' => [
                'required',
                'in:approve,reject',
                new NoMaliciousContent()
            ],
            'rejection_reason' => [
                'required_if:action,reject',
                'max:500',
                'string',
                'regex:/^[a-zA-Z0-9\s\.\,\!\?\-\(\)\'\"]+$/',
                new NoMaliciousContent()
            ],
        ];
    }

    public function messages()
    {
        return [
            'action.required' => 'Please select an action.',
            'action.in' => 'Invalid action selected.',
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting a registration.',
            'rejection_reason.max' => 'Rejection reason must not exceed 500 characters.',
            'rejection_reason.regex' => 'Rejection reason contains invalid characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('rejection_reason')) {
            $this->merge([
                'rejection_reason' => trim($this->rejection_reason ?? ''),
            ]);
        }
    }
}