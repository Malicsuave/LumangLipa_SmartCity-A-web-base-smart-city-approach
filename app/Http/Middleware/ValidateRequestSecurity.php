<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ValidateRequestSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rate limiting for form submissions
        $this->handleRateLimiting($request);
        
        // Sanitize request inputs
        $this->sanitizeRequest($request);
        
        // Check for suspicious patterns
        $this->validateRequestSecurity($request);
        
        // Log security events
        $this->logSecurityEvents($request);
        
        return $next($request);
    }

    /**
     * Handle rate limiting for form submissions
     */
    protected function handleRateLimiting(Request $request): void
    {
        if (!$request->isMethod('POST')) {
            return;
        }

        $key = 'form-submission:' . $request->ip();
        $maxAttempts = 10; // Maximum 10 form submissions per minute
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            Log::warning('Rate limit exceeded for form submission', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);
            
            abort(429, 'Too many form submission attempts. Please wait before trying again.');
        }

        RateLimiter::hit($key, $decayMinutes * 60);
    }

    /**
     * Sanitize request inputs
     */
    protected function sanitizeRequest(Request $request): void
    {
        $input = $request->all();
        $sanitized = $this->recursiveSanitize($input);
        $request->replace($sanitized);
    }

    /**
     * Recursively sanitize array data
     */
    protected function recursiveSanitize($data): array
    {
        if (!is_array($data)) {
            return is_string($data) ? $this->sanitizeString($data) : $data;
        }

        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->recursiveSanitize($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize individual string values
     */
    protected function sanitizeString(string $value): string
    {
        // Remove null bytes
        $value = str_replace("\0", '', $value);
        
        // Remove control characters except tabs, newlines, and carriage returns
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        
        // Normalize Unicode characters
        if (function_exists('normalizer_normalize')) {
            $value = normalizer_normalize($value, Normalizer::FORM_C);
        }
        
        return $value;
    }

    /**
     * Validate request for security threats
     */
    protected function validateRequestSecurity(Request $request): void
    {
        $suspiciousPatterns = [
            // SQL Injection patterns
            '/(\bUNION\b.*\bSELECT\b)|(\bSELECT\b.*\bFROM\b)|(\bINSERT\b.*\bINTO\b)/i',
            '/(\bUPDATE\b.*\bSET\b)|(\bDELETE\b.*\bFROM\b)|(\bDROP\b.*\bTABLE\b)/i',
            
            // XSS patterns
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            
            // Path traversal
            '/\.\.[\/\\\\]/',
            
            // Command injection
            '/[;&|`$(){}[\]]/i',
            
            // File inclusion
            '/(include|require)(_once)?\s*\(/i',
        ];

        $allInput = json_encode($request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $allInput)) {
                Log::critical('Suspicious request pattern detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'pattern' => $pattern,
                    'user_id' => $request->user()?->id,
                ]);
                
                abort(400, 'Invalid request data detected.');
            }
        }
    }

    /**
     * Log security-related events
     */
    protected function logSecurityEvents(Request $request): void
    {
        // Log sensitive form submissions
        if ($request->isMethod('POST') && $this->isSensitiveRoute($request)) {
            Log::info('Secure form submission', [
                'route' => $request->route()?->getName(),
                'ip' => $request->ip(),
                'user_id' => $request->user()?->id,
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Log requests with suspicious user agents
        $userAgent = $request->userAgent();
        if ($this->isSuspiciousUserAgent($userAgent)) {
            Log::warning('Suspicious user agent detected', [
                'ip' => $request->ip(),
                'user_agent' => $userAgent,
                'url' => $request->fullUrl(),
            ]);
        }
    }

    /**
     * Check if the route is sensitive
     */
    protected function isSensitiveRoute(Request $request): bool
    {
        $sensitiveRoutes = [
            'admin.approvals.store',
            'admin.approvals.update',
            'admin.profile.update',
            'admin.profile.photo.update',
            'admin.access-requests.approve',
            'admin.access-requests.deny',
        ];

        return in_array($request->route()?->getName(), $sensitiveRoutes);
    }

    /**
     * Check if user agent is suspicious
     */
    protected function isSuspiciousUserAgent(?string $userAgent): bool
    {
        if (!$userAgent) {
            return true;
        }

        $suspiciousAgents = [
            'sqlmap',
            'nikto',
            'nessus',
            'burp',
            'owasp',
            'scanner',
            'bot',
            'crawler',
            'spider',
        ];

        $userAgentLower = strtolower($userAgent);
        foreach ($suspiciousAgents as $agent) {
            if (strpos($userAgentLower, $agent) !== false) {
                return true;
            }
        }

        return false;
    }
}
