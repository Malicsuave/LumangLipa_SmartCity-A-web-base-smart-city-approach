<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\UserActivity;
use App\Http\Middleware\AccountLockoutMiddleware;

class AuthenticateUser
{
    /**
     * Maximum failed login attempts before account lockout
     */
    protected $maxAttempts = 5;
    
    /**
     * Lockout time in minutes
     */
    protected $lockoutTime = 15;

    /**
     * Authenticate the user with enhanced security
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|null
     */
    public function authenticate(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        
        // Check if account is locked
        if (AccountLockoutMiddleware::isAccountLocked($email)) {
            $lockoutEnd = AccountLockoutMiddleware::getLockoutEnd($email);
            $minutesRemaining = now()->diffInMinutes($lockoutEnd);
            
            throw ValidationException::withMessages([
                'email' => ["Account is locked due to too many failed login attempts. Please try again in {$minutesRemaining} minutes."],
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            // Record failed attempt
            if ($user) {
                AccountLockoutMiddleware::recordFailedAttempt($email);
                $this->handleFailedLogin($user, $request);
            }
            
            // Log failed attempt even if user doesn't exist (security logging)
            UserActivity::create([
                'user_id' => $user?->id,
                'activity_type' => 'login_failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $this->detectDeviceType($request->userAgent()),
                'is_suspicious' => true,
                'details' => [
                    'email' => $email,
                    'reason' => $user ? 'invalid_password' : 'user_not_found',
                    'attempts' => AccountLockoutMiddleware::getFailedAttempts($email),
                ],
            ]);
            
            return null;
        }

        // Clear failed attempts on successful login
        AccountLockoutMiddleware::clearAttempts($email);
        
        // Reset user's failed attempts
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        // Log successful login
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'login_success',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'is_suspicious' => false,
            'details' => [
                'login_method' => 'password',
                'session_id' => $request->session()->getId(),
            ],
        ]);

        return $user;
    }

    /**
     * Handle failed login attempt
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function handleFailedLogin(User $user, Request $request)
    {
        // Increment failed attempts
        $user->failed_login_attempts += 1;
        
        // Check if account should be locked
        if ($user->failed_login_attempts >= $this->maxAttempts) {
            $user->locked_until = now()->addMinutes($this->lockoutTime);
            
            // Log account lockout
            UserActivity::create([
                'user_id' => $user->id,
                'activity_type' => 'account_locked',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $this->detectDeviceType($request->userAgent()),
                'is_suspicious' => true,
                'details' => [
                    'failed_attempts' => $user->failed_login_attempts,
                    'lockout_minutes' => $this->lockoutTime,
                ],
            ]);
        } else {
            // Log failed login attempt
            UserActivity::create([
                'user_id' => $user->id,
                'activity_type' => 'login_failed',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $this->detectDeviceType($request->userAgent()),
                'is_suspicious' => false,
                'details' => [
                    'attempt_number' => $user->failed_login_attempts,
                    'max_attempts' => $this->maxAttempts,
                ],
            ]);
        }
        
        $user->save();
    }
    
    /**
     * Detect device type from user agent
     */
    protected function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/android|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }
}