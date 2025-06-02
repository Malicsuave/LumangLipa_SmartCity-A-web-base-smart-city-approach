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
     */
    public function toResponse($request)
    {
        $user = auth()->user();
        
        // Debug logging
        Log::info('LoginResponse: User ID: ' . $user->id);
        Log::info('LoginResponse: User Role: ' . ($user->role ? $user->role->name : 'NULL'));
        
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