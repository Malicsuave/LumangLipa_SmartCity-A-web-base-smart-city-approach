<?php

namespace App\Repositories;

use App\Repositories\Contracts\AnalyticsRepositoryInterface;
use App\Models\Resident;
use App\Models\PreRegistration;
use App\Models\DocumentRequest;
use App\Models\BlotterComplaint;
use App\Models\HealthServiceRequest;
use App\Models\User;
use App\Models\Feedback;
use App\Models\UserActivity;
use App\Models\Announcement;
use App\Models\AnnouncementRegistration;
use App\Models\AgentConversation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    public function getPopulationMetrics(): array
    {
        return Cache::remember('analytics.population_metrics', 300, function () {
            return [
                'total_residents' => Resident::count(),
                'new_residents_this_month' => Resident::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'pending_pre_registrations' => PreRegistration::where('status', 'pending')->count(),
                'approved_pre_registrations_this_month' => PreRegistration::where('status', 'approved')
                    ->whereMonth('updated_at', now()->month)
                    ->whereYear('updated_at', now()->year)
                    ->count()
            ];
        });
    }

    public function getDocumentRequestMetrics(): array
    {
        return Cache::remember('analytics.document_metrics', 300, function () {
            return [
                'total_document_requests' => DocumentRequest::count(),
                'pending_documents' => DocumentRequest::where('status', 'pending')->count(),
                'approved_documents_this_month' => DocumentRequest::where('status', 'approved')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            ];
        });
    }

    public function getComplaintMetrics(): array
    {
        return Cache::remember('analytics.complaint_metrics', 300, function () {
            return [
                'total_complaints' => BlotterComplaint::count(),
                'pending_complaints' => BlotterComplaint::where('status', 'pending')->count(),
                'resolved_complaints_this_month' => BlotterComplaint::where('status', 'resolved')
                    ->whereMonth('updated_at', now()->month)
                    ->whereYear('updated_at', now()->year)
                    ->count()
            ];
        });
    }

    public function getHealthServiceMetrics(): array
    {
        return Cache::remember('analytics.health_metrics', 300, function () {
            return [
                'total_health_requests' => HealthServiceRequest::count(),
                'pending_health_requests' => HealthServiceRequest::where('status', 'pending')->count(),
                'completed_health_requests_this_month' => HealthServiceRequest::where('status', 'completed')
                    ->whereMonth('updated_at', now()->month)
                    ->whereYear('updated_at', now()->year)
                    ->count()
            ];
        });
    }

    public function getGenderDistribution(): array
    {
        return Cache::remember('analytics.gender_distribution', 600, function () {
            return Resident::select('sex', DB::raw('count(*) as count'))
                ->groupBy('sex')
                ->pluck('count', 'sex')
                ->toArray();
        });
    }

    public function getAgeDistribution(): array
    {
        return Cache::remember('analytics.age_distribution', 600, function () {
            return Resident::select(
                DB::raw('CASE 
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 0 AND 17 THEN "0-17"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 35 THEN "18-35"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 36 AND 55 THEN "36-55"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 56 AND 75 THEN "56-75"
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 76 THEN "75+"
                    ELSE NULL
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('birthdate')
            ->where('birthdate', '!=', '')
            ->where('birthdate', '!=', '0000-00-00')
            ->whereRaw('birthdate IS NOT NULL AND birthdate != "" AND birthdate != "0000-00-00" AND STR_TO_DATE(birthdate, "%Y-%m-%d") IS NOT NULL')
            ->groupBy('age_group')
            ->havingRaw('age_group IS NOT NULL')
            ->pluck('count', 'age_group')
            ->toArray();
        });
    }

    public function getMonthlyRegistrations(): array
    {
        return Cache::remember('analytics.monthly_registrations', 300, function () {
            $months = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[$date->format('M Y')] = Resident::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();
            }
            return $months;
        });
    }

    public function getDocumentTypeDistribution(): array
    {
        return Cache::remember('analytics.document_types', 600, function () {
            return DocumentRequest::select('document_type', DB::raw('count(*) as count'))
                ->groupBy('document_type')
                ->pluck('count', 'document_type')
                ->toArray();
        });
    }

    public function getComplaintStatusDistribution(): array
    {
        return Cache::remember('analytics.complaint_status', 300, function () {
            return BlotterComplaint::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        });
    }

    public function getSystemUsageMetrics(): array
    {
        return Cache::remember('analytics.system_usage', 300, function () {
            return [
                'total_users' => User::count(),
                'active_users_today' => User::whereDate('last_login_at', Carbon::today())->count(),
                'active_users_this_week' => User::where('last_login_at', '>=', Carbon::now()->subWeek())->count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            ];
        });
    }

    // NEW: Feedback Analytics
    public function getFeedbackMetrics(): array
    {
        return Cache::remember('analytics.feedback_metrics', 300, function () {
            return $this->getFeedbackMetricsRaw();
        });
    }

    // NEW: Real-time version without cache
    public function getFeedbackMetricsRaw(): array
    {
        $averageRating = Feedback::avg('rating') ?? 0;
        $totalFeedbacks = Feedback::count();
        $ratingDistribution = Feedback::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('count', 'rating')
            ->toArray();
        
        return [
            'total_feedbacks' => $totalFeedbacks,
            'average_rating' => round($averageRating, 2),
            'rating_distribution' => $ratingDistribution,
            'satisfaction_rate' => $totalFeedbacks > 0 ? 
                round((Feedback::where('rating', '>=', 4)->count() / $totalFeedbacks) * 100, 1) : 0,
            'service_type_ratings' => Feedback::select('service_type', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as count'))
                ->whereNotNull('service_type')
                ->groupBy('service_type')
                ->get()
                ->keyBy('service_type')
                ->toArray()
        ];
    }

    // NEW: User Activity Analytics
    public function getUserActivityMetrics(): array
    {
        return Cache::remember('analytics.user_activity_metrics', 300, function () {
            return [
                'total_activities' => UserActivity::count(),
                'activities_today' => UserActivity::whereDate('created_at', Carbon::today())->count(),
                'activities_this_week' => UserActivity::where('created_at', '>=', Carbon::now()->subWeek())->count(),
                'device_distribution' => UserActivity::select('device_type', DB::raw('count(*) as count'))
                    ->whereNotNull('device_type')
                    ->groupBy('device_type')
                    ->pluck('count', 'device_type')
                    ->toArray(),
                'activity_types' => UserActivity::select('activity_type', DB::raw('count(*) as count'))
                    ->groupBy('activity_type')
                    ->orderByDesc('count')
                    ->pluck('count', 'activity_type')
                    ->toArray(),
                'suspicious_activities' => UserActivity::where('is_suspicious', true)->count(),
                'peak_hours' => UserActivity::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                    ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('count', 'hour')
                    ->toArray()
            ];
        });
    }

    // NEW: Announcement Analytics
    public function getAnnouncementMetrics(): array
    {
        return Cache::remember('analytics.announcement_metrics', 300, function () {
            $totalAnnouncements = Announcement::count();
            $activeAnnouncements = Announcement::where('is_active', true)->count();
            $totalRegistrations = AnnouncementRegistration::count();
            
            return [
                'total_announcements' => $totalAnnouncements,
                'active_announcements' => $activeAnnouncements,
                'total_registrations' => $totalRegistrations,
                'registrations_this_month' => AnnouncementRegistration::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'announcement_types' => Announcement::select('type', DB::raw('count(*) as count'))
                    ->whereNotNull('type')
                    ->groupBy('type')
                    ->pluck('count', 'type')
                    ->toArray(),
                'most_popular_announcements' => Announcement::leftJoin('announcement_registrations', 'announcements.id', '=', 'announcement_registrations.announcement_id')
                    ->select('announcements.title', DB::raw('COUNT(announcement_registrations.id) as registrations'))
                    ->groupBy('announcements.id', 'announcements.title')
                    ->orderByDesc('registrations')
                    ->limit(5)
                    ->get()
                    ->toArray(),
                'capacity_utilization' => Announcement::whereNotNull('max_slots')
                    ->where('max_slots', '>', 0)
                    ->select('title', 'max_slots', 'current_slots', 
                        DB::raw('ROUND((current_slots / max_slots) * 100, 1) as utilization_percentage'))
                    ->orderByDesc('utilization_percentage')
                    ->limit(10)
                    ->get()
                    ->toArray()
            ];
        });
    }

    // NEW: Chatbot/Agent Conversation Analytics
    public function getChatbotMetrics(): array
    {
        return Cache::remember('analytics.chatbot_metrics', 300, function () {
            return [
                'total_conversations' => AgentConversation::distinct('session_id')->count(),
                'conversations_today' => AgentConversation::distinct('session_id')->whereDate('created_at', Carbon::today())->count(),
                'conversations_this_week' => AgentConversation::distinct('session_id')->where('created_at', '>=', Carbon::now()->subWeek())->count(),
                'total_responses' => AgentConversation::where('sender_type', '!=', 'user')->count(),
                'queue_status_distribution' => AgentConversation::select('queue_status', DB::raw('count(*) as count'))
                    ->whereNotNull('queue_status')
                    ->groupBy('queue_status')
                    ->pluck('count', 'queue_status')
                    ->toArray(),
                'user_status_distribution' => AgentConversation::select('user_status', DB::raw('count(*) as count'))
                    ->whereNotNull('user_status')
                    ->groupBy('user_status')
                    ->pluck('count', 'user_status')
                    ->toArray(),
                'average_response_time' => AgentConversation::whereNotNull('conversation_started_at')
                    ->whereNotNull('conversation_completed_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, conversation_started_at, conversation_completed_at)) as avg_minutes')
                    ->value('avg_minutes') ?? 0,
                'completed_conversations' => AgentConversation::whereNotNull('conversation_completed_at')->distinct('session_id')->count(),
                'active_conversations' => AgentConversation::where('user_status', 'active')->distinct('session_id')->count(),
                'peak_chat_hours' => AgentConversation::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                    ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('count', 'hour')
                    ->toArray()
            ];
        });
    }

    /**
     * Get activity trends for the last 30 days
     */
    public function getActivityTrends(): array
    {
        return Cache::remember('analytics.activity_trends', 300, function () {
            $trends = [];
            $startDate = Carbon::now()->subDays(30);
            
            for ($i = 0; $i < 30; $i++) {
                $date = $startDate->copy()->addDays($i);
                $dateKey = $date->format('M j');
                
                $trends[$dateKey] = UserActivity::whereDate('created_at', $date->format('Y-m-d'))
                    ->count();
            }
            
            return $trends;
        });
    }
}