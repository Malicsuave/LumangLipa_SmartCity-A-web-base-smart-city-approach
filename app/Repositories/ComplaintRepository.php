<?php

namespace App\Repositories;

use App\Models\Complaint;
use App\Repositories\Contracts\ComplaintRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20)
    {
        $query = Complaint::with(['approver', 'resident']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['complaint_type'])) {
            $query->where('complaint_type', $filters['complaint_type']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('complaint_type', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($residentQuery) use ($search) {
                      $residentQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
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
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
            'this_month' => Complaint::whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->count(),
            'resolved_this_month' => Complaint::where('status', 'resolved')
                                              ->whereMonth('resolved_at', now()->month)
                                              ->whereYear('resolved_at', now()->year)
                                              ->count(),
        ];
    }

    public function getByStatus(string $status)
    {
        return Complaint::where('status', $status)
                       ->with(['approver', 'resident'])
                       ->orderBy('created_at', 'desc')
                       ->get();
    }

    public function getByCategory(string $category)
    {
        return Complaint::where('complaint_type', $category)
                       ->with(['approver', 'resident'])
                       ->orderBy('created_at', 'desc')
                       ->get();
    }

    public function getRecentComplaints(int $limit = 10)
    {
        return Complaint::with(['approver', 'resident'])
                       ->orderBy('created_at', 'desc')
                       ->limit($limit)
                       ->get();
    }

    public function getResolutionTimeMetrics(): array
    {
        return Complaint::where('status', 'resolved')
                       ->whereNotNull('resolved_at')
                       ->select(
                           DB::raw('AVG(DATEDIFF(resolved_at, created_at)) as avg_days'),
                           DB::raw('MIN(DATEDIFF(resolved_at, created_at)) as min_days'),
                           DB::raw('MAX(DATEDIFF(resolved_at, created_at)) as max_days')
                       )
                       ->first()
                       ->toArray();
    }

    public function create(array $data)
    {
        return Complaint::create($data);
    }

    public function update(int $id, array $data)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update($data);
        return $complaint->fresh();
    }

    public function updateStatus(int $id, string $status, ?string $resolution = null): bool
    {
        $updateData = ['status' => $status];
        
        if ($resolution) {
            $updateData['resolution'] = $resolution;
        }

        switch ($status) {
            case 'in_progress':
                $updateData['started_at'] = now();
                break;
            case 'resolved':
                $updateData['resolved_at'] = now();
                break;
            case 'closed':
                $updateData['closed_at'] = now();
                break;
        }

        return Complaint::where('id', $id)->update($updateData) > 0;
    }

    public function getMonthlyComplaintCounts(): array
    {
        return Complaint::select(
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

    public function getCategoryDistribution(): array
    {
        return Complaint::select('complaint_type', DB::raw('COUNT(*) as count'))
                       ->groupBy('complaint_type')
                       ->orderBy('count', 'desc')
                       ->get()
                       ->toArray();
    }

    public function getPriorityDistribution(): array
    {
        return [];
    }
}