<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('admin.profile')->with('status', 'Profile information updated successfully.');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:1024'], // 1MB Max
        ]);

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
}