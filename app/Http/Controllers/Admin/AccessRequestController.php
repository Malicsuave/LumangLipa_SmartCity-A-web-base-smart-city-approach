<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\AdminApproval;
use App\Models\User;
use App\Notifications\AccessRequestApproved;
use App\Notifications\AccessRequestDenied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccessRequestController extends Controller
{
    /**
     * Display a listing of the access requests.
     */
    public function index()
    {
        $pendingRequests = AccessRequest::where('status', 'pending')
            ->with(['user', 'role'])
            ->latest('requested_at')
            ->get();
            
        $processedRequests = AccessRequest::where('status', '!=', 'pending')
            ->with(['user', 'role', 'approver', 'denier'])
            ->latest('updated_at')
            ->take(20)
            ->get();
            
        return view('admin.access-requests.index', [
            'pendingRequests' => $pendingRequests,
            'processedRequests' => $processedRequests
        ]);
    }

    /**
     * Show details of a specific access request.
     */
    public function show(AccessRequest $accessRequest)
    {
        $accessRequest->load(['user', 'role']);
        
        return view('admin.access-requests.show', [
            'accessRequest' => $accessRequest
        ]);
    }

    /**
     * Approve an access request.
     */
    public function approve(Request $request, AccessRequest $accessRequest)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Only process if request is still pending
        if ($accessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $admin = Auth::user();
        
        // Update the access request
        $accessRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
            'admin_notes' => $request->notes
        ]);
        
        // Update the user's role
        $user = User::findOrFail($accessRequest->user_id);
        $user->update([
            'role_id' => $accessRequest->role_id
        ]);
        
        // Create or update an admin approval record so the approved user appears in the approvals page
        AdminApproval::updateOrCreate(
            ['email' => $user->email],
            [
                'role_id' => $accessRequest->role_id,
                'is_active' => true,
                'approved_by' => $admin->email,
                'approved_at' => now(),
                'notes' => $request->notes
            ]
        );
        
        Log::info('Access request approved', [
            'request_id' => $accessRequest->id,
            'user_id' => $user->id,
            'role_id' => $accessRequest->role_id,
            'admin_id' => $admin->id
        ]);
        
        // Send notification to the user
        $user->notify(new AccessRequestApproved($accessRequest));
        
        return redirect()->route('admin.access-requests.index')
            ->with('success', 'Access request approved successfully. The user has been notified.');
    }

    /**
     * Deny an access request.
     */
    public function deny(Request $request, AccessRequest $accessRequest)
    {
        $request->validate([
            'denial_reason' => 'required|string|min:10|max:500',
        ]);

        // Only process if request is still pending
        if ($accessRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $admin = Auth::user();
        
        // Update the access request
        $accessRequest->update([
            'status' => 'denied',
            'denied_at' => now(),
            'denied_by' => $admin->id,
            'admin_notes' => $request->denial_reason
        ]);
        
        Log::info('Access request denied', [
            'request_id' => $accessRequest->id,
            'user_id' => $accessRequest->user_id,
            'admin_id' => $admin->id,
            'reason' => $request->denial_reason
        ]);
        
        // Send notification to the user
        $user = User::findOrFail($accessRequest->user_id);
        $user->notify(new AccessRequestDenied($accessRequest));
        
        return redirect()->route('admin.access-requests.index')
            ->with('success', 'Access request denied. The user has been notified.');
    }
}