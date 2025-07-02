<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopulationMergeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-population');
    }

    public function rules()
    {
        return [
            'type' => 'required|in:resident_family_member,duplicate_family_members',
            'action' => 'required|in:keep_resident,promote_family_member,merge_data',
            'primary_id' => 'required|integer',
            'secondary_id' => 'required_unless:action,keep_resident|integer',
            'merge_data' => 'array',
            'merge_data.*' => 'string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Duplicate type is required.',
            'type.in' => 'Invalid duplicate type selected.',
            'action.required' => 'Action is required.',
            'action.in' => 'Invalid action selected.',
            'primary_id.required' => 'Primary record ID is required.',
            'primary_id.integer' => 'Primary record ID must be a valid number.',
            'secondary_id.required_unless' => 'Secondary record ID is required for this action.',
            'secondary_id.integer' => 'Secondary record ID must be a valid number.',
        ];
    }
}