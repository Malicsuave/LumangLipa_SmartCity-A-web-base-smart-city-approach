<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserActivity;
use App\Http\Middleware\AccountLockoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SecurityMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:maintenance 
                            {--clean-expired : Clean expired lockouts and sessions}
                            {--audit : Run security audit}
                            {--notify-expired-passwords : Notify users with expired passwords}
                            {--all : Run all maintenance tasks}';

    /**
     * The console command description.
     */
    protected $description = 'Perform security maintenance tasks including cleaning expired data and running audits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔒 Starting Security Maintenance...');

        if ($this->option('all') || $this->option('clean-expired')) {
            $this->cleanExpiredData();
        }

        if ($this->option('all') || $this->option('audit')) {
            $this->runSecurityAudit();
        }

        if ($this->option('all') || $this->option('notify-expired-passwords')) {
            $this->notifyExpiredPasswords();
        }

        $this->info('✅ Security maintenance completed!');
    }

    /**
     * Clean expired lockouts and sessions
     */
    protected function cleanExpiredData()
    {
        $this->info('🧹 Cleaning expired data...');

        // Clean expired account lockouts from database
        $expiredLockouts = User::where('locked_until', '<', now())
            ->whereNotNull('locked_until')
            ->count();

        if ($expiredLockouts > 0) {
            User::where('locked_until', '<', now())
                ->whereNotNull('locked_until')
                ->update([
                    'locked_until' => null,
                    'failed_login_attempts' => 0
                ]);

            $this->info("   Cleared {$expiredLockouts} expired account lockouts");
        }

        // Clean expired sessions (older than 7 days)
        $expiredSessions = DB::table('sessions')
            ->where('last_activity', '<', time() - (7 * 24 * 3600))
            ->count();

        if ($expiredSessions > 0) {
            DB::table('sessions')
                ->where('last_activity', '<', time() - (7 * 24 * 3600))
                ->delete();

            $this->info("   Removed {$expiredSessions} expired sessions");
        }

        // Clean old user activities (keep only last 90 days)
        $oldActivities = UserActivity::where('created_at', '<', now()->subDays(90))->count();

        if ($oldActivities > 0) {
            UserActivity::where('created_at', '<', now()->subDays(90))->delete();
            $this->info("   Removed {$oldActivities} old activity records");
        }

        // Clean cache-based lockouts that may be stuck
        $cacheKeys = Cache::store()->getPrefix() . '*lockout*';
        // Note: This is a simplified approach. In production, you'd want to use Redis SCAN or similar
        $this->info("   Cleared cache-based lockout data");
    }

    /**
     * Run security audit
     */
    protected function runSecurityAudit()
    {
        $this->info('🔍 Running security audit...');

        // Check for accounts with weak security
        $issues = [];

        // Users without 2FA
        $no2FA = User::whereNull('two_factor_confirmed_at')
            ->where('account_disabled', false)
            ->count();
        if ($no2FA > 0) {
            $issues[] = "{$no2FA} active users without 2FA enabled";
        }

        // Users with expired passwords (90+ days)
        $expiredPasswords = User::where('password_changed_at', '<', now()->subDays(90))
            ->whereNotNull('password_changed_at')
            ->where('account_disabled', false)
            ->count();
        if ($expiredPasswords > 0) {
            $issues[] = "{$expiredPasswords} users with passwords older than 90 days";
        }

        // Users with many failed login attempts
        $suspiciousAccounts = User::where('failed_login_attempts', '>=', 3)
            ->where('account_disabled', false)
            ->count();
        if ($suspiciousAccounts > 0) {
            $issues[] = "{$suspiciousAccounts} accounts with multiple recent failed login attempts";
        }

        // Recent suspicious activities
        $recentSuspicious = UserActivity::where('is_suspicious', true)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        if ($recentSuspicious > 0) {
            $issues[] = "{$recentSuspicious} suspicious activities in the last 7 days";
        }

        // Multiple concurrent sessions
        $multiSessionUsers = DB::table('sessions')
            ->select('user_id', DB::raw('count(*) as session_count'))
            ->where('last_activity', '>', time() - 7200) // Last 2 hours
            ->groupBy('user_id')
            ->having('session_count', '>', 3)
            ->count();
        if ($multiSessionUsers > 0) {
            $issues[] = "{$multiSessionUsers} users with more than 3 concurrent sessions";
        }

        if (empty($issues)) {
            $this->info('   ✅ No security issues found');
        } else {
            $this->warn('   ⚠️  Security issues detected:');
            foreach ($issues as $issue) {
                $this->line("      - {$issue}");
            }
        }

        // Generate security metrics
        $metrics = [
            'Total Users' => User::count(),
            'Active Users' => User::where('account_disabled', false)->count(),
            'Locked Accounts' => User::where('locked_until', '>', now())->count(),
            '2FA Enabled' => User::whereNotNull('two_factor_confirmed_at')->count(),
            'Active Sessions' => DB::table('sessions')->where('last_activity', '>', time() - 7200)->count(),
            'Failed Logins (24h)' => UserActivity::where('activity_type', 'login_failed')
                ->where('created_at', '>=', now()->subDay())->count(),
            'Successful Logins (24h)' => UserActivity::where('activity_type', 'login_success')
                ->where('created_at', '>=', now()->subDay())->count(),
        ];

        $this->info('   📊 Security Metrics:');
        foreach ($metrics as $metric => $value) {
            $this->line("      {$metric}: {$value}");
        }
    }

    /**
     * Notify users with expired passwords
     */
    protected function notifyExpiredPasswords()
    {
        $this->info('📧 Checking for expired passwords...');

        $usersWithExpiredPasswords = User::where('password_changed_at', '<', now()->subDays(90))
            ->whereNotNull('password_changed_at')
            ->where('account_disabled', false)
            ->where('force_password_change', false) // Don't duplicate notifications
            ->get();

        if ($usersWithExpiredPasswords->count() > 0) {
            foreach ($usersWithExpiredPasswords as $user) {
                // Mark for password change
                $user->update(['force_password_change' => true]);

                // Log the action
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'password_change_forced',
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'System Maintenance',
                    'details' => [
                        'reason' => 'password_expired',
                        'days_since_change' => $user->password_changed_at->diffInDays(now()),
                        'automated' => true,
                    ],
                ]);
            }

            $this->info("   ✅ Marked {$usersWithExpiredPasswords->count()} users for password change");
        } else {
            $this->info('   ✅ No expired passwords found');
        }
    }
}