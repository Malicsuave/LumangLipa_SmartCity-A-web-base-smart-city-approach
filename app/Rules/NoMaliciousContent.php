<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoMaliciousContent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        // Check for script tags and JavaScript
        if (preg_match('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', $value)) {
            $fail('The :attribute contains potentially harmful script content.');
            return;
        }

        // Check for JavaScript protocols
        if (preg_match('/javascript:/i', $value)) {
            $fail('The :attribute contains potentially harmful JavaScript content.');
            return;
        }

        // Check for event handlers
        if (preg_match('/on\w+\s*=/i', $value)) {
            $fail('The :attribute contains potentially harmful event handlers.');
            return;
        }

        // Check for dangerous HTML tags
        $dangerousTags = ['iframe', 'object', 'embed', 'form', 'input', 'link', 'meta', 'style'];
        foreach ($dangerousTags as $tag) {
            if (stripos($value, "<{$tag}") !== false) {
                $fail("The :attribute contains potentially harmful HTML content ({$tag} tag).");
                return;
            }
        }

        // Check for SQL injection attempts
        $sqlPatterns = [
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bSELECT\b.*\bFROM\b)/i',
            '/(\bINSERT\b.*\bINTO\b)/i',
            '/(\bUPDATE\b.*\bSET\b)/i',
            '/(\bDELETE\b.*\bFROM\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/(;|\|\||&&)/i'
        ];

        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail('The :attribute contains potentially harmful database commands.');
                return;
            }
        }

        // Check for file path traversal
        if (preg_match('/\.\.[\/\\\\]/', $value)) {
            $fail('The :attribute contains potentially harmful path traversal content.');
            return;
        }

        // Check for PHP code injection
        if (preg_match('/<\?php|<\?=|\?>/i', $value)) {
            $fail('The :attribute contains potentially harmful PHP code.');
            return;
        }
    }
}
