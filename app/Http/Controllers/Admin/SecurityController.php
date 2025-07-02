<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use App\Http\Middleware\SessionSecurityMiddleware;
use App\Http\Middleware\AccountLockoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SecurityController extends Controller
{
    /**
     * Display the security dashboard
     */
    public function dashboard()
    {
        // Get security metrics
        $metrics = $this->getSecurityMetrics();
        
        // Get recent security events
        $recentEvents = $this->getRecentSecurityEvents();
        
        // Get locked accounts
        $lockedAccounts = $this->getLockedAccounts();
        
        // Get suspicious activities
        $suspiciousActivities = $this->getSuspiciousActivities();
        
        // Get active sessions
        $activeSessions = $this->getActiveSessionsCount();
        
        return view('admin.security.dashboard', compact(
            'metrics',
            'recentEvents', 
            'lockedAccounts',
            'suspiciousActivities',
            'activeSessions'
        ));
    }
    
    /**
     * Get security metrics for the dashboard
     */
    private function getSecurityMetrics(): array
    {
        $today = Carbon::today();
        $lastWeek = Carbon::today()->subWeek();
        $lastMonth = Carbon::today()->subMonth();
        
        try {
            return [
                // Login statistics
                'successful_logins_today' => UserActivity::where('activity_type', 'login_success')
                    ->whereDate('created_at', $today)
                    ->count(),
                
                'failed_logins_today' => UserActivity::where('activity_type', 'login_failed')
                    ->whereDate('created_at', $today)
                    ->count(),
                
                'successful_logins_week' => UserActivity::where('activity_type', 'login_success')
                    ->where('created_at', '>=', $lastWeek)
                    ->count(),
                
                'failed_logins_week' => UserActivity::where('activity_type', 'login_failed')
                    ->where('created_at', '>=', $lastWeek)
                    ->count(),
                
                // Account security - using safe queries
                'locked_accounts' => $this->getLockedAccountsCount(),
                'accounts_with_2fa' => $this->getTwoFactorAccountsCount(),
                'total_active_users' => User::where('account_disabled', false)->orWhereNull('account_disabled')->count(),
                'accounts_requiring_password_change' => $this->getPasswordChangeRequiredCount(),
                
                // Session security
                'active_sessions' => $this->getActiveSessionsCountSafe(),
                
                'suspicious_activities_today' => UserActivity::where('is_suspicious', true)
                    ->whereDate('created_at', $today)
                    ->count(),
                
                'suspicious_activities_week' => UserActivity::where('is_suspicious', true)
                    ->where('created_at', '>=', $lastWeek)
                    ->count(),
                
                // Password security
                'expired_passwords' => $this->getExpiredPasswordsCount(),
            ];
        } catch (\Exception $e) {
            // Return default values if there's an error
            return [
                'successful_logins_today' => 0,
                'failed_logins_today' => 0,
                'successful_logins_week' => 0,
                'failed_logins_week' => 0,
                'locked_accounts' => 0,
                'accounts_with_2fa' => 0,
                'total_active_users' => User::count(),
                'accounts_requiring_password_change' => 0,
                'active_sessions' => 0,
                'suspicious_activities_today' => 0,
                'suspicious_activities_week' => 0,
                'expired_passwords' => 0,
            ];
        }
    }
    
    /**
     * Safely get locked accounts count
     */
    private function getLockedAccountsCount(): int
    {
        try {
            return User::where('locked_until', '>', now())->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Safely get two factor accounts count
     */
    private function getTwoFactorAccountsCount(): int
    {
        try {
            return User::whereNotNull('two_factor_confirmed_at')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Safely get password change required count
     */
    private function getPasswordChangeRequiredCount(): int
    {
        try {
            return User::where('force_password_change', true)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Safely get active sessions count
     */
    private function getActiveSessionsCountSafe(): int
    {
        try {
            return DB::table('sessions')
                ->where('last_activity', '>', time() - 7200) // Last 2 hours
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Safely get expired passwords count
     */
    private function getExpiredPasswordsCount(): int
    {
        try {
            return User::where('password_changed_at', '<', now()->subDays(90))
                ->whereNotNull('password_changed_at')
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get recent security events
     */
    private function getRecentSecurityEvents(): array
    {
        try {
            return UserActivity::with('user')
                ->whereIn('activity_type', [
                    'login_success', 'login_failed', 'account_locked', 
                    'session_timeout', 'suspicious_session', 'password_changed'
                ])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'user' => $activity->user ? $activity->user->name : 'Unknown',
                        'email' => $activity->user ? $activity->user->email : 'N/A',
                        'type' => $activity->activity_type,
                        'ip_address' => $activity->ip_address ?? 'Unknown',
                        'user_agent' => $activity->user_agent ?? 'Unknown',
                        'is_suspicious' => $activity->is_suspicious ?? false,
                        'details' => json_decode($activity->details ?? '{}', true),
                        'created_at' => $activity->created_at,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get currently locked accounts
     */
    private function getLockedAccounts(): array
    {
        try {
            return User::where('locked_until', '>', now())
                ->orderBy('locked_until', 'desc')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'failed_attempts' => $user->failed_login_attempts ?? 0,
                        'locked_until' => $user->locked_until,
                        'unlock_time' => $user->locked_until ? $user->locked_until->diffForHumans() : 'Unknown',
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get suspicious activities
     */
    private function getSuspiciousActivities(): array
    {
        try {
            return UserActivity::with('user')
                ->where('is_suspicious', true)
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'user' => $activity->user ? $activity->user->name : 'Unknown',
                        'email' => $activity->user ? $activity->user->email : 'N/A',
                        'type' => $activity->activity_type,
                        'ip_address' => $activity->ip_address ?? 'Unknown',
                        'details' => json_decode($activity->details ?? '{}', true),
                        'created_at' => $activity->created_at,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get active sessions count by user
     */
    private function getActiveSessionsCount(): array
    {
        try {
            return DB::table('sessions')
                ->join('users', 'sessions.user_id', '=', 'users.id')
                ->where('sessions.last_activity', '>', time() - 7200) // Last 2 hours
                ->groupBy('users.id', 'users.name', 'users.email')
                ->select('users.id', 'users.name', 'users.email', DB::raw('count(*) as session_count'))
                ->orderBy('session_count', 'desc')
                ->limit(10)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Unlock a user account
     */
    public function unlockAccount(Request $request, User $user)
    {
        $user->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
            'security_notes' => ($user->security_notes ?? '') . "\n" . 
                "[" . now() . "] Account unlocked by " . auth()->user()->email
        ]);
        
        // Clear cache-based lockout as well
        AccountLockoutMiddleware::clearAttempts($user->email);
        
        // Log the unlock action
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'account_unlocked',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => [
                'unlocked_by' => auth()->user()->email,
                'admin_action' => true,
            ],
        ]);
        
        return response()->json(['success' => true, 'message' => 'Account unlocked successfully']);
    }
    
    /**
     * Force password change for a user
     */
    public function forcePasswordChange(Request $request, User $user)
    {
        $user->update([
            'force_password_change' => true,
            'security_notes' => ($user->security_notes ?? '') . "\n" . 
                "[" . now() . "] Password change forced by " . auth()->user()->email
        ]);
        
        // Log the action
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'password_change_forced',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => [
                'forced_by' => auth()->user()->email,
                'admin_action' => true,
            ],
        ]);
        
        return response()->json(['success' => true, 'message' => 'User will be required to change password on next login']);
    }
    
    /**
     * Disable user account
     */
    public function disableAccount(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $user->update([
            'account_disabled' => true,
            'security_notes' => ($user->security_notes ?? '') . "\n" . 
                "[" . now() . "] Account disabled by " . auth()->user()->email . 
                " - Reason: " . $request->reason
        ]);
        
        // Terminate all active sessions
        DB::table('sessions')->where('user_id', $user->id)->delete();
        
        // Log the action
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'account_disabled',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => [
                'disabled_by' => auth()->user()->email,
                'reason' => $request->reason,
                'admin_action' => true,
            ],
        ]);
        
        return response()->json(['success' => true, 'message' => 'Account disabled successfully']);
    }
    
    /**
     * Get user's active sessions
     */
    public function getUserSessions(User $user)
    {
        $sessions = SessionSecurityMiddleware::getActiveSessions($user->id);
        
        return response()->json($sessions);
    }
    
    /**
     * Terminate a specific user session
     */
    public function terminateSession(Request $request, User $user, $sessionId)
    {
        $success = SessionSecurityMiddleware::terminateSession($sessionId, $user->id);
        
        if ($success) {
            // Log the termination
            UserActivity::create([
                'user_id' => $user->id,
                'activity_type' => 'session_terminated_by_admin',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => [
                    'terminated_by' => auth()->user()->email,
                    'session_id' => $sessionId,
                    'admin_action' => true,
                ],
            ]);
            
            return response()->json(['success' => true, 'message' => 'Session terminated successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Session not found or already expired'], 404);
    }
    
    /**
     * Get security analytics data for charts
     */
    public function getAnalytics(Request $request)
    {
        $days = $request->get('days', 30);
        $startDate = Carbon::today()->subDays($days);
        
        // Login attempts over time
        $loginData = UserActivity::selectRaw('DATE(created_at) as date, 
                                            SUM(CASE WHEN activity_type = "login_success" THEN 1 ELSE 0 END) as successful,
                                            SUM(CASE WHEN activity_type = "login_failed" THEN 1 ELSE 0 END) as failed')
            ->where('created_at', '>=', $startDate)
            ->whereIn('activity_type', ['login_success', 'login_failed'])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Suspicious activities over time
        $suspiciousData = UserActivity::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->where('is_suspicious', true)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        return response()->json([
            'login_data' => $loginData,
            'suspicious_data' => $suspiciousData,
        ]);
    }
}