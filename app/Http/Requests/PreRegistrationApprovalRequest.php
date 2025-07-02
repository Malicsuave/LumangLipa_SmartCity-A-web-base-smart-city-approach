<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreRegistrationApprovalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-pre-registrations');
    }

    public function rules()
    {
        return [
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|max:500',
        ];
    }

    public function messages()
    {
        return [
            'action.required' => 'Please select an action.',
            'action.in' => 'Invalid action selected.',
            'rejection_reason.required_if' => 'Rejection reason is required when rejecting a registration.',
            'rejection_reason.max' => 'Rejection reason must not exceed 500 characters.',
        ];
    }
}