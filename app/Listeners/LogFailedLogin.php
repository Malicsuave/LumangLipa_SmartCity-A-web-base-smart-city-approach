<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFailedLogin
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
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        // Skip tracking for non-web guards (like API)
        if ($event->guard !== 'web') {
            return;
        }
        
        // If we have credentials but no user, it's a failed attempt with incorrect email
        if (!$event->user && isset($event->credentials['email'])) {
            // Check if the user exists
            $user = User::where('email', $event->credentials['email'])->first();
            
            // If user exists, increment failed attempts
            if ($user) {
                $this->handleFailedAttempt($user);
            }
            
            return;
        }
        
        // If we have a user, it's a failed password attempt for an existing user
        if ($event->user) {
            $this->handleFailedAttempt($event->user);
        }
    }
    
    /**
     * Handle a failed login attempt for a user.
     *
     * @param \App\Models\User $user
     */
    protected function handleFailedAttempt(User $user): void
    {
        // Increment failed attempts
        $user->failed_login_attempts += 1;
        $request = request();
        
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
            
            // Store the lockout time in session
            session()->flash('auth.account_locked', $this->lockoutTime);
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
     * Simple device type detection from user agent.
     *
     * @param string $userAgent
     * @return string
     */
    protected function detectDeviceType($userAgent)
    {
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/android|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }
}
