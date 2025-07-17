<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AccountLockoutMiddleware
{
    /**
     * Maximum failed login attempts before lockout
     */
    private const MAX_ATTEMPTS = 5;
    
    /**
     * Lockout duration in minutes
     */
    private const LOCKOUT_DURATION = 15;
    
    /**
     * Progressive lockout multiplier
     */
    private const LOCKOUT_MULTIPLIER = 2;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to login attempts
        if ($request->routeIs('login') && $request->isMethod('POST')) {
            $email = $request->input('email');
            
            if (self::isAccountLocked($email)) {
                $lockoutEnd = self::getLockoutEnd($email);
                $minutesRemaining = Carbon::now()->diffInMinutes($lockoutEnd);
                
                return redirect()->back()
                    ->withErrors([
                        'email' => "Account is locked due to too many failed login attempts. Please try again in {$minutesRemaining} minutes."
                    ])
                    ->withInput($request->only('email'));
            }
        }
        
        return $next($request);
    }
    
    /**
     * Check if account is currently locked
     */
    public static function isAccountLocked(string $email): bool
    {
        $lockoutEnd = Cache::get("lockout.{$email}");
        
        if (!$lockoutEnd) {
            return false;
        }
        
        if (Carbon::now()->greaterThan($lockoutEnd)) {
            // Lockout expired, clear it
            Cache::forget("lockout.{$email}");
            Cache::forget("attempts.{$email}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Get lockout end time
     */
    public static function getLockoutEnd(string $email): ?Carbon
    {
        return Cache::get("lockout.{$email}");
    }
    
    /**
     * Record failed login attempt
     */
    public static function recordFailedAttempt(string $email): void
    {
        $key = "attempts.{$email}";
        $attempts = Cache::get($key, 0) + 1;
        
        // Store attempts for 1 hour
        Cache::put($key, $attempts, now()->addHour());
        
        if ($attempts >= self::MAX_ATTEMPTS) {
            self::lockAccount($email, $attempts);
        }
    }
    
    /**
     * Lock account with progressive penalties
     */
    public static function lockAccount(string $email, int $attempts): void
    {
        // Progressive lockout: base time * multiplier^(excess attempts)
        $excessAttempts = max(0, $attempts - self::MAX_ATTEMPTS);
        $lockoutMinutes = self::LOCKOUT_DURATION * pow(self::LOCKOUT_MULTIPLIER, $excessAttempts);
        
        // Cap at 24 hours
        $lockoutMinutes = min($lockoutMinutes, 1440);
        
        $lockoutEnd = now()->addMinutes($lockoutMinutes);
        Cache::put("lockout.{$email}", $lockoutEnd, $lockoutEnd);
        
        // Log the lockout
        \Illuminate\Support\Facades\Log::warning("Account locked", [
            'email' => $email,
            'attempts' => $attempts,
            'lockout_minutes' => $lockoutMinutes,
            'lockout_until' => $lockoutEnd->toDateTimeString(),
            'ip' => request()->ip(),
        ]);
    }
    
    /**
     * Clear failed attempts on successful login
     */
    public static function clearAttempts(string $email): void
    {
        Cache::forget("attempts.{$email}");
        Cache::forget("lockout.{$email}");
    }
    
    /**
     * Get current failed attempts count
     */
    public static function getFailedAttempts(string $email): int
    {
        return Cache::get("attempts.{$email}", 0);
    }
}