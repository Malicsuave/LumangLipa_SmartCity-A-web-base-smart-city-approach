<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProfileUpdateRequest;
use App\Http\Requests\ProfilePhotoUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminProfileController extends Controller
{
    public function updateProfile(AdminProfileUpdateRequest $request)
    {
        $user = Auth::user();
        
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('admin.profile')->with('status', 'Profile information updated successfully.');
    }

    public function updateProfilePhoto(ProfilePhotoUpdateRequest $request)
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

        return redirect()->route('admin.profile')->with('status', 'Profile photo updated successfully.');
    }

    public function deleteProfilePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        return redirect()->route('admin.profile')->with('status', 'Profile photo removed successfully.');
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
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        // Prevent using the same password
        if (Hash::check($request->new_password, $user->password)) {
            throw ValidationException::withMessages([
                'new_password' => ['The new password must be different from the current password.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'Password changed successfully!');
    }
}