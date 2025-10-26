<?php

namespace App\Repositories;

use App\Models\BlotterComplaint;
use App\Repositories\Contracts\ComplaintRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20)
    {
        $query = BlotterComplaint::with(['approver', 'resident']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('complainants', 'like', "%{$search}%")
                  ->orWhere('respondents', 'like', "%{$search}%")
                  ->orWhere('complaint_details', 'like', "%{$search}%")
                  ->orWhere('case_number', 'like', "%{$search}%")
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
            'total' => BlotterComplaint::count(),
            'pending' => BlotterComplaint::where('status', 'pending')->count(),
            'in_progress' => BlotterComplaint::where('status', 'in_progress')->count(),
            'resolved' => BlotterComplaint::where('status', 'resolved')->count(),
            'closed' => BlotterComplaint::where('status', 'closed')->count(),
            'this_month' => BlotterComplaint::whereMonth('created_at', now()->month)
                                   ->whereYear('created_at', now()->year)
                                   ->count(),
            'resolved_this_month' => BlotterComplaint::where('status', 'resolved')
                                              ->whereMonth('resolved_at', now()->month)
                                              ->whereYear('resolved_at', now()->year)
                                              ->count(),
        ];
    }

    public function getByStatus(string $status)
    {
        return BlotterComplaint::where('status', $status)
                       ->with(['approver', 'resident'])
                       ->orderBy('created_at', 'desc')
                       ->get();
    }

    public function getByCategory(string $category)
    {
        // Since BlotterComplaint is unified, search by status instead
        return BlotterComplaint::where('status', $category)
                       ->with(['approver', 'resident'])
                       ->orderBy('created_at', 'desc')
                       ->get();
    }

    public function getRecentComplaints(int $limit = 10)
    {
        return BlotterComplaint::with(['approver', 'resident'])
                       ->orderBy('created_at', 'desc')
                       ->limit($limit)
                       ->get();
    }

    public function getResolutionTimeMetrics(): array
    {
        return BlotterComplaint::where('status', 'resolved')
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
        return BlotterComplaint::create($data);
    }

    public function update(int $id, array $data)
    {
        $complaint = BlotterComplaint::findOrFail($id);
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

        return BlotterComplaint::where('id', $id)->update($updateData) > 0;
    }

    public function getMonthlyComplaintCounts(): array
    {
        return BlotterComplaint::select(
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
        // Return status distribution instead of complaint types
        return BlotterComplaint::select('status', DB::raw('COUNT(*) as count'))
                       ->groupBy('status')
                       ->orderBy('count', 'desc')
                       ->get()
                       ->toArray();
    }

    public function getPriorityDistribution(): array
    {
        return [];
    }
}
