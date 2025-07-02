<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminApprovalStoreRequest;
use App\Http\Requests\AdminApprovalUpdateRequest;
use App\Models\AdminApproval;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminApprovalController extends Controller
{
    /**
     * Display a listing of admin approvals.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all admin approvals with their roles
        $approvals = AdminApproval::with('role', 'user')->orderBy('created_at', 'desc')->get();
        $roles = Role::all();
        
        return view('admin.approvals.index', compact('approvals', 'roles'));
    }
    
    /**
     * Show the form for creating a new admin approval.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.approvals.create', compact('roles'));
    }
    
    /**
     * Store a newly created admin approval in storage.
     *
     * @param  AdminApprovalStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminApprovalStoreRequest $request)
    {
        $validated = $request->validated();
        $adminUser = Auth::user();
        
        AdminApproval::create([
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'is_active' => true,
            'approved_by' => $adminUser->email,
            'approved_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);
        
        Log::info('New admin approval created', [
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'created_by' => $adminUser->email,
        ]);
        
        return redirect()->route('admin.approvals.index')
            ->with('success', 'Admin approval created successfully. This Gmail account can now access the admin dashboard with the assigned role.');
    }
    
    /**
     * Show the form for editing the specified admin approval.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $approval = AdminApproval::findOrFail($id);
        $roles = Role::all();
        
        return view('admin.approvals.edit', compact('approval', 'roles'));
    }
    
    /**
     * Update the specified admin approval in storage.
     *
     * @param  AdminApprovalUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminApprovalUpdateRequest $request, $id)
    {
        $approval = AdminApproval::findOrFail($id);
        $validated = $request->validated();
        $adminUser = Auth::user();
        
        $approval->update([
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'is_active' => $validated['is_active'],
            'approved_by' => $adminUser->email,
            'approved_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);
        
        // If the user exists, update their role as well
        $user = User::where('email', $validated['email'])->first();
        if ($user) {
            $user->update(['role_id' => $validated['is_active'] ? $validated['role_id'] : null]);
        }
        
        Log::info('Admin approval updated', [
            'id' => $approval->id,
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'is_active' => $validated['is_active'],
            'updated_by' => $adminUser->email,
        ]);
        
        return redirect()->route('admin.approvals.index')
            ->with('success', 'Admin approval updated successfully.');
    }
    
    /**
     * Remove the specified admin approval from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $approval = AdminApproval::findOrFail($id);
        $email = $approval->email;
        
        // Remove role from user if exists
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update(['role_id' => null]);
        }
        
        $approval->delete();
        
        Log::info('Admin approval deleted', [
            'id' => $id,
            'email' => $email,
            'deleted_by' => Auth::user()->email,
        ]);
        
        return redirect()->route('admin.approvals.index')
            ->with('success', 'Admin approval deleted successfully. This Gmail account no longer has admin access.');
    }
    
    /**
     * Toggle the active status of an admin approval.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($id)
    {
        $approval = AdminApproval::findOrFail($id);
        $approval->is_active = !$approval->is_active;
        $approval->approved_by = Auth::user()->email;
        $approval->approved_at = now();
        $approval->save();
        
        // Update user role if exists
        $user = User::where('email', $approval->email)->first();
        if ($user) {
            $user->update(['role_id' => $approval->is_active ? $approval->role_id : null]);
        }
        
        Log::info('Admin approval status toggled', [
            'id' => $approval->id,
            'email' => $approval->email,
            'is_active' => $approval->is_active,
            'toggled_by' => Auth::user()->email,
        ]);
        
        $status = $approval->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.approvals.index')
            ->with('success', "Admin approval {$status} successfully.");
    }

    /**
     * Toggle the active status of an admin approval.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus($id)
    {
        $approval = AdminApproval::findOrFail($id);
        $approval->is_active = !$approval->is_active;
        $approval->approved_by = Auth::user()->email;
        $approval->approved_at = now();
        $approval->save();
        
        // Update user role if exists
        $user = User::where('email', $approval->email)->first();
        if ($user) {
            $user->update(['role_id' => $approval->is_active ? $approval->role_id : null]);
        }
        
        Log::info('Admin approval status toggled', [
            'id' => $approval->id,
            'email' => $approval->email,
            'is_active' => $approval->is_active,
            'toggled_by' => Auth::user()->email,
        ]);
        
        $status = $approval->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.approvals.index')
            ->with('success', "Admin approval {$status} successfully.");
    }
    
    /**
     * Process pending access requests from users.
     *
     * @return \Illuminate\View\View
     */
    public function pendingRequests()
    {
        // In a complete implementation, you would fetch pending requests from a database table
        // For now, we'll just return a view with placeholder data
        
        return view('admin.approvals.pending');
    }
}
