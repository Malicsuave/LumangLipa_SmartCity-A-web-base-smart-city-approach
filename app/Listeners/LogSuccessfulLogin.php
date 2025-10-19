<?php

namespace App\Listeners;

use App\Models\UserActivity;
use App\Notifications\SuspiciousLoginAttempt;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
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
    public function handle(Login $event): void
    {
        // Skip tracking for non-web guards (like API)
        if ($event->guard !== 'web') {
            return;
        }
        
        $user = $event->user;
        $request = request();
        
        // Store the last login information directly on the user
        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        
        // Reset failed login attempts
        $user->failed_login_attempts = 0;
        $user->locked_until = null;
        $user->save();
        
        // Check if this login is from a different device/location
        $isSuspicious = false;
        $lastLogin = UserActivity::where('user_id', $user->id)
            ->where('activity_type', 'login')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($lastLogin && $lastLogin->ip_address !== $request->ip()) {
            $isSuspicious = true;
        }
        
        // Log the successful login activity
        $activity = UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'is_suspicious' => $isSuspicious,
            'details' => [
                'method' => 'password', // or 'google', etc.
                'remember' => $request->has('remember'),
                'location_changed' => $isSuspicious,
            ],
        ]);
        
        // Send notification for suspicious logins
        if ($isSuspicious) {
            $user->notify(new SuspiciousLoginAttempt($activity));
        }
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
