<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Rules\NoMaliciousContent;

class PasswordUpdateRequest extends FormRequest
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
            'current_password' => [
                'required',
                'string',
                new NoMaliciousContent(),
            ],
            'new_password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed',
                'different:current_password',
                new NoMaliciousContent(),
            ],
            'new_password_confirmation' => [
                'required',
                'string',
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
            'new_password.required' => 'The new password field is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.different' => 'The new password must be different from the current password.',
            'new_password_confirmation.required' => 'The password confirmation field is required.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure password fields are trimmed but preserve spaces within
        if ($this->has('new_password')) {
            $this->merge([
                'new_password' => trim($this->new_password),
            ]);
        }
        
        if ($this->has('new_password_confirmation')) {
            $this->merge([
                'new_password_confirmation' => trim($this->new_password_confirmation),
            ]);
        }
    }
}
