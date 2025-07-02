<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivity;
use Carbon\Carbon;

class SessionSecurityMiddleware
{
    /**
     * Maximum concurrent sessions per user
     */
    private const MAX_SESSIONS = 3;
    
    /**
     * Session timeout in minutes
     */
    private const SESSION_TIMEOUT = 120; // 2 hours
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();
            
            // Check session timeout
            $this->checkSessionTimeout($request);
            
            // Track active session
            $this->trackActiveSession($user, $request, $currentSessionId);
            
            // Enforce concurrent session limits
            $this->enforceConcurrentSessionLimits($user, $currentSessionId);
            
            // Detect suspicious session activity
            $this->detectSuspiciousActivity($user, $request);
        }
        
        return $next($request);
    }
    
    /**
     * Check for session timeout
     */
    private function checkSessionTimeout(Request $request): void
    {
        $lastActivity = $request->session()->get('last_activity');
        
        if ($lastActivity && Carbon::createFromTimestamp($lastActivity)->addMinutes(self::SESSION_TIMEOUT)->isPast()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Log session timeout
            UserActivity::create([
                'user_id' => Auth::id(),
                'activity_type' => 'session_timeout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => [
                    'timeout_minutes' => self::SESSION_TIMEOUT,
                    'last_activity' => $lastActivity,
                ],
            ]);
            
            abort(419, 'Session expired due to inactivity');
        }
        
        // Update last activity
        $request->session()->put('last_activity', time());
    }
    
    /**
     * Track active session in database
     */
    private function trackActiveSession($user, Request $request, string $sessionId): void
    {
        DB::table('sessions')->updateOrInsert(
            ['id' => $sessionId],
            [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload' => base64_encode(serialize($request->session()->all())),
                'last_activity' => time(),
            ]
        );
    }
    
    /**
     * Enforce concurrent session limits
     */
    private function enforceConcurrentSessionLimits($user, string $currentSessionId): void
    {
        $activeSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>', time() - (self::SESSION_TIMEOUT * 60))
            ->orderBy('last_activity', 'desc')
            ->get();
        
        if ($activeSessions->count() > self::MAX_SESSIONS) {
            // Keep the most recent sessions, terminate the oldest
            $sessionsToTerminate = $activeSessions->slice(self::MAX_SESSIONS);
            
            foreach ($sessionsToTerminate as $session) {
                if ($session->id !== $currentSessionId) {
                    DB::table('sessions')->where('id', $session->id)->delete();
                    
                    // Log session termination
                    UserActivity::create([
                        'user_id' => $user->id,
                        'activity_type' => 'session_terminated',
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'details' => [
                            'reason' => 'concurrent_session_limit',
                            'session_id' => $session->id,
                            'max_sessions' => self::MAX_SESSIONS,
                        ],
                    ]);
                }
            }
        }
    }
    
    /**
     * Detect suspicious session activity
     */
    private function detectSuspiciousActivity($user, Request $request): void
    {
        $recentSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>', time() - 3600) // Last hour
            ->get();
        
        $uniqueIps = $recentSessions->pluck('ip_address')->unique();
        $uniqueUserAgents = $recentSessions->pluck('user_agent')->unique();
        
        $isSuspicious = false;
        $suspiciousReasons = [];
        
        // Multiple IPs in short time
        if ($uniqueIps->count() > 3) {
            $isSuspicious = true;
            $suspiciousReasons[] = 'multiple_ip_addresses';
        }
        
        // Multiple user agents (possible session hijacking)
        if ($uniqueUserAgents->count() > 2) {
            $isSuspicious = true;
            $suspiciousReasons[] = 'multiple_user_agents';
        }
        
        // Geolocation check (basic implementation)
        $currentIp = $request->ip();
        $lastKnownIp = $user->last_login_ip;
        
        if ($lastKnownIp && $this->isGeographicallyDistant($currentIp, $lastKnownIp)) {
            $isSuspicious = true;
            $suspiciousReasons[] = 'geographical_anomaly';
        }
        
        if ($isSuspicious) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity_type' => 'suspicious_session',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_suspicious' => true,
                'details' => [
                    'reasons' => $suspiciousReasons,
                    'unique_ips' => $uniqueIps->count(),
                    'unique_user_agents' => $uniqueUserAgents->count(),
                    'session_count' => $recentSessions->count(),
                ],
            ]);
            
            // Optionally force 2FA verification for suspicious activity
            if (in_array('geographical_anomaly', $suspiciousReasons)) {
                $request->session()->put('requires_additional_verification', true);
            }
        }
    }
    
    /**
     * Basic geographical distance check
     */
    private function isGeographicallyDistant(string $ip1, string $ip2): bool
    {
        // This is a simplified check - in production, you'd use a proper GeoIP service
        // For now, just check if IPs are from different subnets
        $subnet1 = substr($ip1, 0, strrpos($ip1, '.'));
        $subnet2 = substr($ip2, 0, strrpos($ip2, '.'));
        
        return $subnet1 !== $subnet2;
    }
    
    /**
     * Get active sessions for a user
     */
    public static function getActiveSessions($userId): array
    {
        return DB::table('sessions')
            ->where('user_id', $userId)
            ->where('last_activity', '>', time() - (self::SESSION_TIMEOUT * 60))
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity),
                    'is_current' => $session->id === request()->session()->getId(),
                ];
            })
            ->toArray();
    }
    
    /**
     * Terminate a specific session
     */
    public static function terminateSession(string $sessionId, $userId): bool
    {
        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $userId)
            ->delete();
        
        if ($deleted) {
            UserActivity::create([
                'user_id' => $userId,
                'activity_type' => 'session_terminated',
                'ip_address' => request()->ip(),
                'details' => [
                    'reason' => 'manual_termination',
                    'terminated_session_id' => $sessionId,
                ],
            ]);
        }
        
        return $deleted > 0;
    }
}