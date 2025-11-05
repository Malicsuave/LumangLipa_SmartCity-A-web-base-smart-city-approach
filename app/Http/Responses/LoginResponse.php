<?php

namespace App\Http\Responses;

use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Log;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */    public function toResponse($request)
    {
        $user = auth()->user();
        
        // Debug logging
        Log::info('LoginResponse: User ID: ' . $user->id);
        Log::info('LoginResponse: User Role: ' . ($user->role ? $user->role->name : 'NULL'));
        
        // Get intended URL but filter out API routes
        $intended = session('url.intended');
        
        // If the intended URL is an API route, ignore it
        if ($intended && (str_starts_with($intended, url('/api/')) || str_contains($intended, '/api/'))) {
            Log::info('LoginResponse: Clearing API intended URL: ' . $intended);
            session()->forget('url.intended');
            $intended = null;
        }
        
        // Check if the user has admin roles and redirect accordingly
        if ($user->role && in_array($user->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
            Log::info('LoginResponse: Redirecting to admin dashboard');
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }
        
        // For regular users or users without specific admin roles
        Log::info('LoginResponse: Redirecting to regular dashboard');
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}