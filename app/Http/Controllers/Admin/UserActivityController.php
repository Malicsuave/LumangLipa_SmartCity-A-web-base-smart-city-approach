<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserActivityController extends Controller
{
    /**
     * Display the security dashboard with activity metrics
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get login statistics
        $loginStats = [
            'today' => UserActivity::where('activity_type', 'login')
                ->whereDate('created_at', today())
                ->count(),
                
            'week' => UserActivity::where('activity_type', 'login')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
                
            'month' => UserActivity::where('activity_type', 'login')
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ];
        
        // Get failed login attempts
        $failedLoginStats = [
            'today' => UserActivity::where('activity_type', 'login_failed')
                ->whereDate('created_at', today())
                ->count(),
                
            'week' => UserActivity::where('activity_type', 'login_failed')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];
        
        // Get 2FA statistics
        $twoFactorStats = [
            'enabled' => User::whereNotNull('two_factor_secret')->count(),
            'total_users' => User::count(),
        ];
        
        // Get recent suspicious activities
        $suspiciousActivities = UserActivity::with('user')
            ->where('is_suspicious', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Get recent activities for table display
        $recentActivities = UserActivity::with('user')
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();
            
        // Get login activity chart data (last 14 days)
        $loginChartData = [];
        $failedChartData = [];
        
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            // Successful logins
            $loginCount = UserActivity::where('activity_type', 'login')
                ->whereDate('created_at', $date)
                ->count();
                
            // Failed logins
            $failedCount = UserActivity::where('activity_type', 'login_failed')
                ->whereDate('created_at', $date)
                ->count();
                
            $loginChartData[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => $loginCount
            ];
            
            $failedChartData[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => $failedCount
            ];
        }
        
        return view('admin.security.dashboard', [
            'loginStats' => $loginStats,
            'failedLoginStats' => $failedLoginStats,
            'twoFactorStats' => $twoFactorStats,
            'suspiciousActivities' => $suspiciousActivities,
            'recentActivities' => $recentActivities,
            'loginChartData' => json_encode($loginChartData),
            'failedChartData' => json_encode($failedChartData),
        ]);
    }
    
    /**
     * Display the activities list with filters
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ensure only authorized roles can access
        if (!in_array(Auth::user()->role->name ?? '', ['Barangay Captain', 'Barangay Secretary'])) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        // Query for activities with filtering options
        $query = UserActivity::with('user');
        
        // Filter by user if provided
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by activity type if provided
        if ($request->has('activity_type') && !empty($request->activity_type)) {
            $query->where('activity_type', $request->activity_type);
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }
        
        // Get suspicious activities if requested
        if ($request->has('suspicious') && $request->suspicious == 1) {
            $query->where('is_suspicious', true);
        }
        
        // Get activities with pagination
        $activities = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get all admin users for filter dropdown
        $users = User::whereHas('role', function($query) {
            $query->whereIn('name', ['Barangay Captain', 'Barangay Secretary', 'Health Worker', 'Complaint Manager']);
        })->get();
        
        // Get unique activity types for filter dropdown
        $activityTypes = UserActivity::select('activity_type')
            ->distinct()
            ->get()
            ->pluck('activity_type');
        
        // Get statistics for the dashboard
        $stats = [
            'total_logins' => UserActivity::where('activity_type', 'login')->count(),
            'suspicious_activities' => UserActivity::where('is_suspicious', true)->count(),
            'today_activities' => UserActivity::whereDate('created_at', today())->count(),
            'failed_logins' => UserActivity::where('activity_type', 'login_failed')->count(),
        ];
        
        return view('admin.security.activities', [
            'activities' => $activities,
            'users' => $users,
            'activityTypes' => $activityTypes,
            'stats' => $stats,
        ]);
    }
    
    /**
     * View details of a specific user activity.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $activity = UserActivity::with('user')->findOrFail($id);
        
        // Get related activities (same user, same day)
        $relatedActivities = UserActivity::with('user')
            ->where('user_id', $activity->user_id)
            ->whereDate('created_at', $activity->created_at->toDateString())
            ->where('id', '!=', $activity->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.security.activity-details', [
            'activity' => $activity,
            'relatedActivities' => $relatedActivities
        ]);
    }
}
