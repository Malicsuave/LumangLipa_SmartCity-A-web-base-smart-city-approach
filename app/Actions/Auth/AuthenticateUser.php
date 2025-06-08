<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthenticateUser
{
    /**
     * Maximum allowed failed login attempts before locking account
     */
    protected $maxAttempts = 5;
    
    /**
     * Account lockout duration in minutes
     */
    protected $lockoutTime = 30;
    
    /**
     * Authenticate a user and track login activity
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|null
     */
    public function authenticate(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        // Check if user exists
        if (!$user) {
            return null;
        }
        
        // Check if account is locked
        if ($user->locked_until && now()->lt($user->locked_until)) {
            $remainingMinutes = now()->diffInMinutes($user->locked_until);
            
            // Log failed login attempt on locked account
            UserActivity::log($user, 'login_failed', [
                'reason' => 'account_locked',
                'remaining_time' => $remainingMinutes . ' minutes'
            ]);
            
            // Return null with custom error message
            request()->session()->flash('auth.account_locked', $remainingMinutes);
            return null;
        }
        
        // Check password
        if (Hash::check($request->password, $user->password)) {
            // Reset failed login attempts on successful login
            $user->failed_login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            $user->save();
            
            // Log successful login
            UserActivity::log($user, 'login', [
                'method' => 'password',
                'remember' => $request->has('remember'),
            ]);
            
            return $user;
        }
        
        // Handle failed login attempt
        $this->handleFailedLogin($user, $request);
        return null;
    }
    
    /**
     * Handle a failed login attempt
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
            UserActivity::log($user, 'account_locked', [
                'failed_attempts' => $user->failed_login_attempts,
                'lockout_minutes' => $this->lockoutTime,
                'ip_address' => $request->ip()
            ]);
            
            // Flash message for locked account
            request()->session()->flash('auth.account_locked', $this->lockoutTime);
        } else {
            // Log failed login attempt
            UserActivity::log($user, 'login_failed', [
                'attempt_number' => $user->failed_login_attempts,
                'max_attempts' => $this->maxAttempts
            ]);
        }
        
        $user->save();
    }
}