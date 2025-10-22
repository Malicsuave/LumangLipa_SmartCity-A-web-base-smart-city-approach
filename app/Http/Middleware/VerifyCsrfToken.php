<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Allow logout even with expired CSRF token
        'logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $exception) {
            // Log the error for debugging
            Log::warning('CSRF Token Mismatch', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'has_session' => $request->hasSession(),
                'session_id' => $request->session()->getId(),
            ]);

            // For registration forms, preserve session and provide helpful message
            if (str_contains($request->path(), 'residents/create')) {
                // Regenerate token but keep session data
                $request->session()->regenerateToken();
                
                Log::info('CSRF token regenerated for registration form', [
                    'session_id' => $request->session()->getId(),
                    'new_token' => $request->session()->token(),
                ]);
                
                return redirect()->back()
                    ->withInput($request->except('password', 'password_confirmation', '_token', 'confirmation'))
                    ->with('info', 'Please try submitting again - the security token has been refreshed.');
            }

            // For other routes, just redirect back with error
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->withErrors(['csrf' => 'Your session has expired. Please try again.']);
        }
    }
}
