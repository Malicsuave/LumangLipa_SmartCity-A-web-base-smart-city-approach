<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoMaliciousContent;

class ProfilePhotoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'photo' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:1024', // 1MB max
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            // Validate any additional metadata that might be sent
            'description' => [
                'nullable',
                'string',
                'max:500',
                new NoMaliciousContent(),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'photo.required' => 'Please select a photo to upload.',
            'photo.file' => 'The uploaded file must be a valid file.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'The photo must be a file of type: jpg, jpeg, png, or webp.',
            'photo.max' => 'The photo may not be greater than 1MB.',
            'photo.dimensions' => 'The photo dimensions must be between 100x100 and 2000x2000 pixels.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('photo')) {
                $file = $this->file('photo');
                
                // Additional security checks for the uploaded file
                $originalName = $file->getClientOriginalName();
                
                // Check for suspicious file names
                if (preg_match('#[<>:"/\\|?*]#', $originalName) || 
                    strpos($originalName, '..') !== false ||
                    preg_match('#\.(php|phtml|php3|php4|php5|phar|exe|bat|cmd|scr)$#i', $originalName)) {
                    $validator->errors()->add('photo', 'The uploaded file has an invalid or potentially dangerous filename.');
                }
                
                // Check file size more strictly
                if ($file->getSize() > 1048576) { // 1MB in bytes
                    $validator->errors()->add('photo', 'The uploaded file is too large.');
                }
            }
        });
    }
}
