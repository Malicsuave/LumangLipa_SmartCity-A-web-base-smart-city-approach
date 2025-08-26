<?php

namespace App\Repositories;

use App\Models\HealthServiceRequest;
use App\Repositories\Contracts\HealthServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HealthServiceRepository implements HealthServiceRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20)
    {
        $query = HealthServiceRequest::with(['approver', 'resident']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('service_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($residentQuery) use ($search) {
                      $residentQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%")
                                   ->orWhere('barangay_id', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getStatistics(): array
    {
        return [
            'total' => HealthServiceRequest::count(),
            'pending' => HealthServiceRequest::where('status', 'pending')->count(),
            'in_progress' => HealthServiceRequest::where('status', 'in_progress')->count(),
            'completed' => HealthServiceRequest::where('status', 'completed')->count(),
            'cancelled' => HealthServiceRequest::where('status', 'cancelled')->count(),
            'this_month' => HealthServiceRequest::whereMonth('created_at', now()->month)
                                              ->whereYear('created_at', now()->year)
                                              ->count(),
            'completed_this_month' => HealthServiceRequest::where('status', 'completed')
                                                         ->whereMonth('updated_at', now()->month)
                                                         ->whereYear('updated_at', now()->year)
                                                         ->count(),
        ];
    }

    public function getByStatus(string $status)
    {
        return HealthServiceRequest::where('status', $status)
                                  ->with(['approver', 'resident'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
    }

    public function getByServiceType(string $serviceType)
    {
        return HealthServiceRequest::where('service_type', $serviceType)
                                  ->with(['approver', 'resident'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
    }

    public function getRecentRequests(int $limit = 10)
    {
        return HealthServiceRequest::with(['approver', 'resident'])
                                  ->orderBy('created_at', 'desc')
                                  ->limit($limit)
                                  ->get();
    }

    public function getServiceMetrics(): array
    {
        return HealthServiceRequest::where('status', 'completed')
                                  ->whereNotNull('completed_at')
                                  ->select(
                                      DB::raw('AVG(DATEDIFF(completed_at, created_at)) as avg_completion_days'),
                                      DB::raw('MIN(DATEDIFF(completed_at, created_at)) as min_completion_days'),
                                      DB::raw('MAX(DATEDIFF(completed_at, created_at)) as max_completion_days'),
                                      DB::raw('COUNT(*) as total_completed')
                                  )
                                  ->first()
                                  ->toArray();
    }

    public function create(array $data)
    {
        return HealthServiceRequest::create($data);
    }

    public function update(int $id, array $data)
    {
        $healthService = HealthServiceRequest::findOrFail($id);
        $healthService->update($data);
        return $healthService->fresh();
    }

    public function updateStatus(int $id, string $status, ?array $serviceData = null): bool
    {
        $updateData = ['status' => $status];
        
        if ($serviceData) {
            $updateData = array_merge($updateData, $serviceData);
        }

        switch ($status) {
            case 'in_progress':
                $updateData['started_at'] = now();
                break;
            case 'completed':
                $updateData['completed_at'] = now();
                break;
            case 'cancelled':
                $updateData['cancelled_at'] = now();
                break;
        }

        return HealthServiceRequest::where('id', $id)->update($updateData) > 0;
    }

    public function getMonthlyServiceCounts(): array
    {
        return HealthServiceRequest::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('month')
                ->get()
                ->toArray();
    }

    public function getServiceTypeDistribution(): array
    {
        return HealthServiceRequest::select('service_type', DB::raw('COUNT(*) as count'))
                                  ->groupBy('service_type')
                                  ->orderBy('count', 'desc')
                                  ->get()
                                  ->toArray();
    }

    public function getPatientAgeGroups(): array
    {
        $today = Carbon::today();
        
        return [
            'children' => HealthServiceRequest::whereHas('resident', function ($q) use ($today) {
                $q->whereDate('birthdate', '>', $today->copy()->subYears(18));
            })->count(),
            'adults' => HealthServiceRequest::whereHas('resident', function ($q) use ($today) {
                $q->whereDate('birthdate', '<=', $today->copy()->subYears(18))
                  ->whereDate('birthdate', '>', $today->copy()->subYears(60));
            })->count(),
            'seniors' => HealthServiceRequest::whereHas('resident', function ($q) use ($today) {
                $q->whereDate('birthdate', '<=', $today->copy()->subYears(60));
            })->count(),
        ];
    }
}