<?php

namespace App\Repositories;

use App\Repositories\Contracts\AnalyticsRepositoryInterface;
use App\Models\Resident;
use App\Models\PreRegistration;
use App\Models\DocumentRequest;
use App\Models\Complaint;
use App\Models\HealthServiceRequest;
use App\Models\User;
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
                'total_complaints' => Complaint::count(),
                'pending_complaints' => Complaint::where('status', 'pending')->count(),
                'resolved_complaints_this_month' => Complaint::where('status', 'resolved')
                    ->whereMonth('resolved_at', now()->month)
                    ->whereYear('resolved_at', now()->year)
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
            return Complaint::select('status', DB::raw('count(*) as count'))
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
}