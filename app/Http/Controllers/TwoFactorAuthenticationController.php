<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;

class TwoFactorAuthenticationController extends Controller
{
    
    // Enable 2FA
    public function store(Request $request)

    {
        $user = $request->user();
        $request->user()->forceFill([
            'two_factor_secret' => encrypt(app('pragmarx.google2fa')->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(function () {
                return strtoupper(str()->random(10));
            })->all())),
        ])->save();

        
        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard')->with('status', 'Two-Factor Authentication enabled successfully.');
        }

        return redirect('/user/dashboard')->with('status', 'Two-Factor Authentication enabled successfully.');
    
    }

    // Disable 2FA
    public function destroy(Request $request)
    {
        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        // Refresh session to reflect updated data
        $request->user()->refresh();

        // Redirect based on role
        if ($request->user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('status', 'Two-Factor Authentication disabled successfully.');
        }

        return redirect('/admin/dashboard')->with('status', 'Two-Factor Authentication disabled successfully.');
    }
    

    // Regenerate Recovery Codes
    public function recoveryCodes(Request $request)
    {
        $request->user()->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(function () {
                return strtoupper(str()->random(10));
            })->all())),
        ])->save();

        return back()->with('status', 'Recovery codes regenerated successfully.');
    }
}


