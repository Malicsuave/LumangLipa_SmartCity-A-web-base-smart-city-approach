<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Rules\NoMaliciousContent;

class SecurityService
{
    /**
     * Validate and sanitize filters for security
     */
    public function validateAndSanitizeFilters(array $filters, array $allowedFilters): array
    {
        $sanitizedFilters = [];
        
        foreach ($filters as $key => $value) {
            // Only allow whitelisted filter keys
            if (!array_key_exists($key, $allowedFilters)) {
                Log::warning('Unauthorized filter key attempted', [
                    'key' => $key,
                    'user_id' => auth()->id(),
                    'ip' => request()->ip()
                ]);
                continue;
            }
            
            // Validate the filter value
            $validator = Validator::make(
                [$key => $value],
                [$key => $allowedFilters[$key] . '|' . new NoMaliciousContent()]
            );
            
            if ($validator->fails()) {
                Log::warning('Invalid filter value detected', [
                    'key' => $key,
                    'value' => $value,
                    'errors' => $validator->errors()->toArray(),
                    'user_id' => auth()->id(),
                    'ip' => request()->ip()
                ]);
                continue;
            }
            
            $sanitizedFilters[$key] = $this->sanitizeValue($value);
        }
        
        return $sanitizedFilters;
    }

    /**
     * Validate sensitive data with enhanced security checks
     */
    public function validateSensitiveData(array $data, array $rules): void
    {
        // Add NoMaliciousContent rule to all string fields
        foreach ($rules as $field => $rule) {
            if (is_string($rule) && str_contains($rule, 'string')) {
                $rules[$field] = $rule . '|' . new NoMaliciousContent();
            }
        }
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            Log::warning('Sensitive data validation failed', [
                'errors' => $validator->errors()->toArray(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            throw new ValidationException($validator);
        }
    }

    /**
     * Sanitize individual values
     */
    private function sanitizeValue($value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }
        
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Remove control characters except tabs, newlines, and carriage returns
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        
        // Normalize Unicode characters
        if (function_exists('normalizer_normalize')) {
            $value = normalizer_normalize($value, \Normalizer::FORM_C);
        }
        
        // Trim whitespace
        return trim($value);
    }

    /**
     * Check if user has permission for repository operations
     */
    public function validateRepositoryAccess(string $operation, string $entity): bool
    {
        $user = auth()->user();
        
        if (!$user || !$user->role) {
            Log::warning('Unauthorized repository access attempt', [
                'operation' => $operation,
                'entity' => $entity,
                'user_id' => $user?->id,
                'ip' => request()->ip()
            ]);
            return false;
        }
        
        // Define permission matrix
        $permissions = [
            'Barangay Captain' => ['read', 'create', 'update', 'delete'],
            'Barangay Secretary' => ['read', 'create', 'update', 'delete'],
            'Health Worker' => ['read', 'create', 'update'],
            'Staff' => ['read', 'create'],
            'Barangay Kagawad' => ['read'],
        ];
        
        $userRole = $user->role->name;
        $allowedOperations = $permissions[$userRole] ?? [];
        
        if (!in_array($operation, $allowedOperations)) {
            Log::warning('Insufficient permissions for repository operation', [
                'operation' => $operation,
                'entity' => $entity,
                'user_role' => $userRole,
                'user_id' => $user->id,
                'ip' => request()->ip()
            ]);
            return false;
        }
        
        return true;
    }

    /**
     * Log sensitive operations for audit trail
     */
    public function logSensitiveOperation(string $operation, array $context = []): void
    {
        Log::info('Sensitive operation performed', array_merge([
            'operation' => $operation,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toISOString()
        ], $context));
    }

    /**
     * Detect and prevent potential security threats in repository queries
     */
    public function validateQueryParameters(array $parameters): array
    {
        $cleanParameters = [];
        
        foreach ($parameters as $key => $value) {
            // Validate parameter keys
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)) {
                Log::warning('Invalid query parameter key detected', [
                    'key' => $key,
                    'user_id' => auth()->id(),
                    'ip' => request()->ip()
                ]);
                continue;
            }
            
            // Sanitize and validate values
            if (is_string($value)) {
                $value = $this->sanitizeValue($value);
                
                // Check for SQL injection patterns
                if ($this->containsSqlInjectionPattern($value)) {
                    Log::critical('SQL injection attempt detected in query parameters', [
                        'key' => $key,
                        'value' => substr($value, 0, 100),
                        'user_id' => auth()->id(),
                        'ip' => request()->ip()
                    ]);
                    throw new \InvalidArgumentException('Invalid query parameter detected');
                }
            }
            
            $cleanParameters[$key] = $value;
        }
        
        return $cleanParameters;
    }

    /**
     * Check for SQL injection patterns
     */
    private function containsSqlInjectionPattern(string $value): bool
    {
        $patterns = [
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bSELECT\b.*\bFROM\b)/i',
            '/(\bINSERT\b.*\bINTO\b)/i',
            '/(\bUPDATE\b.*\bSET\b)/i',
            '/(\bDELETE\b.*\bFROM\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/(;\s*--)|(;\s*\/\*)/',
            '/(\|\||&&)/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        
        return false;
    }
}