<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RepositorySecurityMiddleware
{
    public function __construct(
        private SecurityService $securityService
    ) {}

    /**
     * Handle an incoming request to repository operations
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log repository access attempt
        Log::info('Repository access attempt', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Validate user authentication
        if (!auth()->check()) {
            Log::warning('Unauthenticated repository access attempt', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            abort(401, 'Authentication required');
        }

        // Validate user has a role
        $user = auth()->user();
        if (!$user->role) {
            Log::warning('Repository access attempt by user without role', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);
            abort(403, 'Insufficient permissions');
        }

        // Rate limiting for repository operations
        $this->enforceRateLimit($request);

        // Validate request parameters for security threats
        $this->validateRequestSecurity($request);

        return $next($request);
    }

    /**
     * Enforce rate limiting for repository operations
     */
    private function enforceRateLimit(Request $request): void
    {
        $key = 'repository_access:' . auth()->id();
        $maxAttempts = 100; // 100 requests per minute per user
        $decayMinutes = 1;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            Log::warning('Repository rate limit exceeded', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            abort(429, 'Too many repository requests');
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, $decayMinutes * 60);
    }

    /**
     * Validate request for security threats
     */
    private function validateRequestSecurity(Request $request): void
    {
        $allInput = $request->all();
        
        // Validate query parameters
        try {
            $this->securityService->validateQueryParameters($allInput);
        } catch (\InvalidArgumentException $e) {
            Log::critical('Security threat detected in repository request', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'input' => json_encode($allInput)
            ]);
            abort(400, 'Invalid request parameters');
        }
    }
}