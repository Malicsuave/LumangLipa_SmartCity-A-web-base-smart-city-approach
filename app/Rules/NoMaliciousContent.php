<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

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

        // Skip validation for empty values
        if (empty(trim($value))) {
            return;
        }

        try {
            // Check for script tags and JavaScript
            if (preg_match('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', $value)) {
                Log::warning('Security violation detected: Script tags', [
                    'attribute' => $attribute,
                    'value' => substr($value, 0, 100) . '...',
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                $fail('The :attribute contains potentially harmful script content.');
                return;
            }

            // Check for JavaScript protocols
            if (preg_match('/javascript:/i', $value)) {
                Log::warning('Security violation detected: JavaScript protocol', [
                    'attribute' => $attribute,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                $fail('The :attribute contains potentially harmful JavaScript content.');
                return;
            }

            // Check for event handlers
            if (preg_match('/on\w+\s*=/i', $value)) {
                Log::warning('Security violation detected: Event handlers', [
                    'attribute' => $attribute,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                $fail('The :attribute contains potentially harmful event handlers.');
                return;
            }

            // Check for dangerous HTML tags
            $dangerousTags = ['iframe', 'object', 'embed', 'form', 'input', 'link', 'meta', 'style'];
            foreach ($dangerousTags as $tag) {
                if (stripos($value, "<{$tag}") !== false) {
                    Log::warning('Security violation detected: Dangerous HTML tag', [
                        'attribute' => $attribute,
                        'tag' => $tag,
                        'ip' => request()->ip(),
                        'user_id' => auth()->id(),
                    ]);
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
                '/(;\s*--)|(;\s*\/\*)/',  // SQL comment patterns
                '/(\|\||&&)/',  // Logical operators often used in injection
            ];

            foreach ($sqlPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    Log::critical('Security violation detected: SQL injection attempt', [
                        'attribute' => $attribute,
                        'pattern' => $pattern,
                        'value' => substr($value, 0, 100) . '...',
                        'ip' => request()->ip(),
                        'user_id' => auth()->id(),
                        'user_agent' => request()->userAgent(),
                    ]);
                    $fail('The :attribute contains potentially harmful database commands.');
                    return;
                }
            }

            // Check for file path traversal
            if (preg_match('#\.\.[\\/\\\\]#', $value)) {
                Log::warning('Security violation detected: Path traversal', [
                    'attribute' => $attribute,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                $fail('The :attribute contains potentially harmful path traversal content.');
                return;
            }

            // Check for PHP code injection
            if (preg_match('/<\?php|<\?=|\?>/i', $value)) {
                Log::critical('Security violation detected: PHP code injection attempt', [
                    'attribute' => $attribute,
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                ]);
                $fail('The :attribute contains potentially harmful PHP code.');
                return;
            }

            // Check for command injection patterns
            $commandPatterns = [
                '/;\s*(ls|cat|grep|find|rm|mv|cp|wget|curl)\s/i',
                '/\|\s*(ls|cat|grep|find|rm|mv|cp|wget|curl)\s/i',
                '/&&\s*(ls|cat|grep|find|rm|mv|cp|wget|curl)\s/i',
            ];

            foreach ($commandPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    Log::critical('Security violation detected: Command injection attempt', [
                        'attribute' => $attribute,
                        'pattern' => $pattern,
                        'ip' => request()->ip(),
                        'user_id' => auth()->id(),
                    ]);
                    $fail('The :attribute contains potentially harmful system commands.');
                    return;
                }
            }

        } catch (\Exception $e) {
            // Log the error but don't fail validation to avoid breaking the application
            Log::error('Error in NoMaliciousContent validation rule', [
                'error' => $e->getMessage(),
                'attribute' => $attribute,
                'ip' => request()->ip(),
            ]);
            
            // In case of validation error, be conservative and allow the input
            // but log it for manual review
            return;
        }
    }
}
