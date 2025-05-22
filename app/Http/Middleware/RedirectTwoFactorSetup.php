<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectTwoFactorSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the response
        $response = $next($request);
        
        // If this is a successful 2FA action and we're being redirected
        if ($response->isRedirection() && 
            $request->is('user/two-factor-authentication') && 
            $request->isMethod('POST') && 
            $request->user() && 
            $request->user()->role && 
            in_array($request->user()->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
            
            // Override the redirection to go to admin profile
            return redirect()->route('admin.profile')
                ->with('status', 'Two-Factor Authentication enabled successfully.');
        }
        
        return $response;
    }
}