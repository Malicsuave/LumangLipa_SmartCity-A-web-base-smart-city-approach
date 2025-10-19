<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoMaliciousContent;

class PopulationMergeRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-population');
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
                'in:resident_family_member,duplicate_family_members',
                new NoMaliciousContent()
            ],
            'action' => [
                'required',
                'in:keep_resident,promote_family_member,merge_data',
                new NoMaliciousContent()
            ],
            'primary_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'secondary_id' => [
                'required_unless:action,keep_resident',
                'integer',
                'min:1'
            ],
            'merge_data' => 'array',
            'merge_data.*' => [
                'string',
                'max:255',
                new NoMaliciousContent()
            ]
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
            'primary_id.min' => 'Primary record ID must be a positive number.',
            'secondary_id.required_unless' => 'Secondary record ID is required for this action.',
            'secondary_id.integer' => 'Secondary record ID must be a valid number.',
            'secondary_id.min' => 'Secondary record ID must be a positive number.',
            'merge_data.*.max' => 'Merge data values must not exceed 255 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize merge_data if present
        if ($this->has('merge_data') && is_array($this->merge_data)) {
            $sanitizedMergeData = [];
            foreach ($this->merge_data as $key => $value) {
                if (is_string($value)) {
                    $sanitizedMergeData[$key] = trim($value);
                } else {
                    $sanitizedMergeData[$key] = $value;
                }
            }
            $this->merge(['merge_data' => $sanitizedMergeData]);
        }
    }
}