<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user needs to change password
            if ($user->needsPasswordChange() || $user->isPasswordExpired()) {
                // Don't redirect if already on password change pages
                if (!$request->routeIs(['admin.profile', 'password.*', 'logout'])) {
                    return redirect()->route('admin.profile')
                        ->withFragment('security')
                        ->with('force_password_change', true)
                        ->with('warning', 'You must change your password before continuing.');
                }
            }
            
            // Check if account is disabled
            if ($user->isAccountDisabled()) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Your account has been disabled. Please contact an administrator.');
            }
        }
        
        return $next($request);
    }
}