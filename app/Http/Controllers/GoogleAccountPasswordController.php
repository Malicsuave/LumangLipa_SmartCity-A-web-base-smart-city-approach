<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GoogleAccountPasswordController extends Controller
{
    /**
     * Show the form for setting a password for Google-authenticated accounts.
     * 
     * @return \Illuminate\View\View
     */
    public function showSetPasswordForm()
    {
        return view('auth.set-password');
    }

    /**
     * Set a password for a Google-authenticated account.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                new \App\Rules\StrongPassword(),
                'confirmed'
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.profile')->with('status', 'Password set successfully. You can now enable Two-Factor Authentication.');
    }

    /**
     * Update password for Google-authenticated accounts via modal form.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => [
                'required',
                'string',
                new \App\Rules\StrongPassword(),
                'confirmed'
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.profile')
                ->withErrors($validator)
                ->with('from_password_modal', true);
        }

        $user = Auth::user();
        
        // Verify this is a Google-authenticated account
        if (!$user->google_id) {
            return redirect()->route('admin.profile')
                ->with('error', 'This action is only available for Google-authenticated accounts.')
                ->with('security_error', true);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.profile')
            ->with('status', 'Password set successfully. You can now enable Two-Factor Authentication.')
            ->with('security_error', true);
    }
}
