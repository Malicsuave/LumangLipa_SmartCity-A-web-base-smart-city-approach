<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class AdminTwoFactorController extends Controller
{
    /**
     * Enable two-factor authentication for admin users
     */
    public function enable(Request $request)
    {
        // Log the action for debugging
        Log::info('Admin 2FA enable method called');
        
        $user = $request->user();
        
        // Validate password field
        $request->validate([
            'password' => 'required',
        ]);
        
        // Check if password matches
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Password validation failed for admin 2FA enable');
            
            // Return to admin profile with error as validation error and flag for JavaScript
            return redirect()
                ->route('admin.profile')
                ->withErrors([
                    'password' => 'The provided password is incorrect.',
                ])
                ->with('from_2fa', true)
                ->withInput();
        }
        
        // Create Google2FA instance and generate secret key
        $google2fa = new Google2FA();
        
        // Enable two-factor auth with confirmation timestamp
        $user->forceFill([
            'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(function () {
                return strtoupper(str()->random(10));
            })->all())),
            'two_factor_confirmed_at' => now(), // This is crucial for 2FA to work during login
        ])->save();
        
        // Success - redirect back to admin profile with flag for JavaScript
        return redirect()
            ->route('admin.profile')
            ->with('status', 'Two-Factor Authentication enabled successfully.')
            ->with('from_2fa', true);
    }
}