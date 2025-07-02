<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = $value;
        
        // Minimum length check
        if (strlen($password) < 8) {
            $fail('The :attribute must be at least 8 characters long.');
            return;
        }
        
        // Maximum length check (prevent DoS attacks)
        if (strlen($password) > 128) {
            $fail('The :attribute must not exceed 128 characters.');
            return;
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $fail('The :attribute must contain at least one uppercase letter.');
            return;
        }
        
        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $fail('The :attribute must contain at least one lowercase letter.');
            return;
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            $fail('The :attribute must contain at least one number.');
            return;
        }
        
        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
            $fail('The :attribute must contain at least one special character (!@#$%^&*()_+-=[]{};\':"|,.<>?/).');
            return;
        }
        
        // Check for common weak passwords
        $commonPasswords = [
            'password', 'password123', '123456', '123456789', 'qwerty',
            'abc123', 'password1', 'admin', 'letmein', 'welcome',
            'monkey', '1234567890', 'iloveyou', 'princess', 'rockyou'
        ];
        
        if (in_array(strtolower($password), $commonPasswords)) {
            $fail('The :attribute cannot be a commonly used password.');
            return;
        }
        
        // Check for sequential characters (123, abc, etc.)
        if (preg_match('/(?:012|123|234|345|456|567|678|789|890|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i', $password)) {
            $fail('The :attribute cannot contain sequential characters (e.g., 123, abc).');
            return;
        }
        
        // Check for repeated characters (aaa, 111, etc.)
        if (preg_match('/(.)\1{2,}/', $password)) {
            $fail('The :attribute cannot contain more than 2 consecutive identical characters.');
            return;
        }
    }
}