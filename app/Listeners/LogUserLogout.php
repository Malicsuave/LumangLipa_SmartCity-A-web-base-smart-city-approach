<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event)
    {
        $user = $event->user;
        // Convert to User model if needed
        if ($user instanceof \Illuminate\Contracts\Auth\Authenticatable && method_exists($user, 'getAuthIdentifier')) {
            $userId = $user->getAuthIdentifier();
            $userModel = \App\Models\User::find($userId);
        } else {
            $userModel = $user;
        }
        if ($userModel) {
            activity()
                ->causedBy($userModel)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'session_id' => session()->getId(),
                ])
                ->log('logout');
        }
    }
} 