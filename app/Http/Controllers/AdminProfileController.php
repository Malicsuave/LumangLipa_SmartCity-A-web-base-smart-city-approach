<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\AdminProfileUpdateRequest;
use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    /**
     * Display the admin profile page.
     */
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    /**
     * Update the admin profile information (route expects update, not updateProfile).
     */
    public function update(AdminProfileUpdateRequest $request)
    {
        return $this->updateProfile($request);
    }

    public function updateProfile(AdminProfileUpdateRequest $request)
    {
        $user = Auth::user();
        
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('admin.profile')->with('profile_status', 'Profile information updated successfully.');
    }

    public function updatePhoto(ProfilePhotoUpdateRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // Delete old profile photo if it exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store the new photo
        $path = $request->file('photo')->store('profile-photos', 'public');
        
        $user->profile_photo_path = $path;
        $user->save();

        return redirect()->route('admin.profile')->with('profile_status', 'Profile photo updated successfully.');
    }

    public function deleteProfilePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        return redirect()->route('admin.profile')->with('profile_status', 'Profile photo removed successfully.');
    }

    public function debugProfile()
    {
        $user = Auth::user();
        dd([
            'name' => $user->name,
            'profile_photo_path' => $user->profile_photo_path,
            'profile_photo_url' => $user->profile_photo_url,
            'has_profile_photo_trait' => in_array('Laravel\Jetstream\HasProfilePhoto', class_uses_recursive($user)),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->getAuthPassword())) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ])->with('show_security_tab', true);
        }

        // Prevent using the same password
        if (Hash::check($request->new_password, $user->getAuthPassword())) {
            return back()->withErrors([
                'new_password' => 'The new password must be different from the current password.',
            ])->with('show_security_tab', true);
        }

        $user->forceFill([
            'password' => Hash::make($request->new_password),
            'password_changed_at' => now(),
            'force_password_change' => false,
        ])->save();

        return back()->with([
            'security_status' => 'Password changed successfully!',
            'show_security_tab' => true
        ]);
    }
}