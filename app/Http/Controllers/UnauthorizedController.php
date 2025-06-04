<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\Role;
use App\Models\User;
use App\Notifications\AccessRequestSubmitted;
use App\Notifications\NewAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class UnauthorizedController extends Controller
{
    /**
     * Display the unauthorized access page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $roles = Role::all();
        
        // Check if user already has a pending request
        $pendingRequest = AccessRequest::where('user_id', $user->id)
            ->whereNull('approved_at')
            ->whereNull('denied_at')
            ->first();
        
        return view('unauthorized', [
            'user' => $user,
            'email' => $user->email,
            'roles' => $roles,
            'pendingRequest' => $pendingRequest
        ]);
    }
    
    /**
     * Handle admin access request submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestAccess(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'role_requested' => 'required|string|exists:roles,name',
            'reason' => 'required|string|min:10|max:500',
        ]);
        
        $user = Auth::user();
        
        // Check if there's already a pending request
        $existingRequest = AccessRequest::where('user_id', $user->id)
            ->whereNull('approved_at')
            ->whereNull('denied_at')
            ->first();
            
        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending access request. Please wait for administrators to review it.');
        }
        
        // Get the role ID from the role name
        $role = Role::where('name', $request->role_requested)->first();
        
        // Create a new access request
        $accessRequest = AccessRequest::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'role_id' => $role->id,
            'reason' => $request->reason,
            'status' => 'pending',
            'requested_at' => now(),
        ]);
        
        Log::info('Access request submitted', [
            'request_id' => $accessRequest->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'role_requested' => $request->role_requested,
        ]);
        
        // Send notification to the user about their request
        $user->notify(new AccessRequestSubmitted($accessRequest));
        
        // Send notification to all Barangay Captains
        $captains = User::whereHas('role', function($query) {
            $query->where('name', 'Barangay Captain');
        })->get();
        
        Notification::send($captains, new NewAccessRequest($accessRequest));
        
        return redirect()->back()->with('status', 'Your request has been submitted. You will receive an email notification once your request has been reviewed.');
    }
}
