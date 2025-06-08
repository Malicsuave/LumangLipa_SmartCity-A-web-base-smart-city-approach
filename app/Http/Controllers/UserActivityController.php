<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    /**
     * Display the user's activity history.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get user activities with pagination
        $activities = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Count suspicious activities
        $suspiciousCount = UserActivity::where('user_id', $user->id)
            ->where('is_suspicious', true)
            ->count();
            
        // Get device statistics
        $deviceStats = UserActivity::where('user_id', $user->id)
            ->where('activity_type', 'login')
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->get();
            
        return view('user.activities', [
            'activities' => $activities,
            'suspiciousCount' => $suspiciousCount,
            'deviceStats' => $deviceStats,
            'user' => $user
        ]);
    }
    
    /**
     * Display the admin view of all user activities.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex(Request $request)
    {
        // Ensure user is an admin
        if (!in_array(Auth::user()->role->name ?? '', ['Barangay Captain', 'Barangay Secretary'])) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        // Build query with filters
        $query = UserActivity::with('user');
        
        // Filter by user if specified
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by activity type if specified
        if ($request->has('activity_type') && !empty($request->activity_type)) {
            $query->where('activity_type', $request->activity_type);
        }
        
        // Filter by suspicious activities
        if ($request->has('suspicious') && $request->suspicious == 1) {
            $query->where('is_suspicious', true);
        }
        
        // Get activities with pagination
        $activities = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get all users for the filter dropdown
        $users = \App\Models\User::all();
        
        // Get activity types for filter dropdown
        $activityTypes = UserActivity::select('activity_type')
            ->distinct()
            ->pluck('activity_type');
        
        return view('admin.activities', [
            'activities' => $activities,
            'users' => $users,
            'activityTypes' => $activityTypes,
        ]);
    }
    
    /**
     * Clear all non-suspicious activities for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearHistory(Request $request)
    {
        $user = Auth::user();
        
        // Delete non-suspicious activities older than 24 hours
        UserActivity::where('user_id', $user->id)
            ->where('is_suspicious', false)
            ->where('created_at', '<', now()->subHours(24))
            ->delete();
        
        return redirect()->route('user.activities')
            ->with('success', 'Activity history cleared successfully.');
    }
}
