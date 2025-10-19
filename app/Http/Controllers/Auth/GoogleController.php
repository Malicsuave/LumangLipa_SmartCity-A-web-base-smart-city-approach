<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AdminApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        // Clear any existing Google OAuth session data
        session()->forget(['google_oauth_state', 'google_user']);
        
        // Force Google to show the account chooser every time
        return Socialite::driver('google')
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Log the Google user information
            Log::info('Google user authenticated', [
                'email' => $googleUser->email,
                'name' => $googleUser->name
            ]);
            
            // Check if this Gmail is pre-approved for admin access
            $adminApproval = AdminApproval::findApprovedEmail($googleUser->email);
            
            // Check if user already exists
            $user = User::where('email', $googleUser->email)->first();
            
            // If user doesn't exist, create them
            if (!$user) {
                Log::info('Creating new user from Google authentication', [
                    'email' => $googleUser->email
                ]);
                
                // Determine role_id based on approval status
                $roleId = $adminApproval ? $adminApproval->role_id : null;
                
                // Create user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->id,
                    'role_id' => $roleId,
                ]);
            } else {
                Log::info('User already exists', [
                    'user_id' => $user->id,
                    'current_role_id' => $user->role_id,
                ]);
                
                // Update the google_id if it doesn't exist
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                // Assign or update role if user is pre-approved
                if ($adminApproval && $user->role_id !== $adminApproval->role_id) {
                    Log::info('Updating user role based on admin approval', [
                        'old_role_id' => $user->role_id,
                        'new_role_id' => $adminApproval->role_id
                    ]);
                    $user->role_id = $adminApproval->role_id;
                    $user->save();
                }
            }
            
            // Reload user with role relationship
            $user->load('role');
            
            // Log the user in
            Auth::login($user, true);
            
            // Log user state before redirect decision
            Log::info('User state before redirect', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'has_role' => $user->role ? true : false,
                'role_name' => $user->role ? $user->role->name : 'No role',
            ]);
            
            // Check if user has a role (approved admin)
            if ($user->role) {
                Log::info('Redirecting authorized admin to dashboard', [
                    'role' => $user->role->name
                ]);
                
                // Redirect based on role
                if (in_array($user->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
                    return redirect()->route('admin.dashboard')
                        ->with('status', 'Welcome, ' . $user->role->name . '!');
                }
                
                return redirect('/dashboard')
                    ->with('status', 'Welcome back, ' . $user->name . '!');
            }
            
            // User doesn't have a role - check for pending request
            $pendingRequest = \App\Models\AccessRequest::where('user_id', $user->id)
                ->whereNull('approved_at')
                ->whereNull('denied_at')
                ->first();
            
            Log::info('Redirecting unauthorized user to access denied page', [
                'has_pending_request' => $pendingRequest ? true : false
            ]);
            
            if ($pendingRequest) {
                return redirect()->route('unauthorized.access')
                    ->with('info', 'Your access request is still pending approval. You will be notified via email when it\'s processed.');
            }
            
            return redirect()->route('unauthorized.access')
                ->with('warning', 'Your account needs approval before accessing the system. Please submit a request.');
                
        } catch (\Exception $e) {
            Log::error('Google authentication error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get the role of the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRole()
    {
        $user = Auth::user();
        
        if ($user && $user->role) {
            return response()->json([
                'success' => true,
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'description' => $user->role->description
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No role assigned or user not authenticated'
        ]);
    }
}
