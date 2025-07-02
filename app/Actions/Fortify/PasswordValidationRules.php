<?php

namespace App\Actions\Fortify;

use App\Rules\StrongPassword;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            new StrongPassword(),
            'confirmed',
        ];
    }
}
