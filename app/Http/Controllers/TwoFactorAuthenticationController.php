<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationController extends Controller
{
    // Enable 2FA with strict validation
    public function store(Request $request)
    {
        // Log access to the controller method for debugging
        Log::info('2FA store method accessed by user: ' . $request->user()->id);
        
        // Validate password field
        $validated = $request->validate([
            'password' => 'required',
        ]);
        
        $user = $request->user();
        
        // Strict password check
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Invalid password provided for 2FA enable by user: ' . $user->id);
            
            // Use direct return to the security tab with error
            return redirect()
                ->route('admin.profile')
                ->withFragment('security')
                ->withErrors(['password' => 'The provided password is incorrect.'])
                ->with('from_2fa', true);
        }
        
        try {
            // Create Google2FA instance and generate secret key
            $google2fa = new Google2FA();
            
            // Generate 2FA secrets with confirmation timestamp
            $user->forceFill([
                'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
                'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(function () {
                    return strtoupper(str()->random(10));
                })->all())),
                'two_factor_confirmed_at' => now(), // This is crucial for 2FA to work during login
            ])->save();
            
            Log::info('2FA enabled successfully for user: ' . $user->id);
            
            // Direct return to the profile page with success message and security flag
            return redirect()
                ->route('admin.profile')
                ->withFragment('security')
                ->with('status', 'Two-Factor Authentication enabled successfully.')
                ->with('from_2fa', true);
                
        } catch (\Exception $e) {
            Log::error('Failed to enable 2FA: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.profile')
                ->withFragment('security')
                ->withErrors(['general' => 'Failed to enable Two-Factor Authentication: ' . $e->getMessage()])
                ->with('from_2fa', true);
        }
    }

    // Disable 2FA
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null, // Also clear the confirmation timestamp
        ])->save();

        // Refresh session to reflect updated data
        $user->refresh();

        return redirect()->route('admin.profile')
            ->with('status', 'Two-Factor Authentication disabled successfully.')
            ->with('from_2fa', true);
    }
    
    // Regenerate Recovery Codes
    public function recoveryCodes(Request $request)
    {
        $request->user()->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(function () {
                return strtoupper(str()->random(10));
            })->all())),
        ])->save();

        return back()
            ->with('status', 'Recovery codes regenerated successfully.')
            ->with('from_2fa', true);
    }
}


