<?php

namespace App\Services;

use App\Repositories\Contracts\ComplaintRepositoryInterface;
use App\Services\ErrorHandlingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ComplaintService
{
    public function __construct(
        private ComplaintRepositoryInterface $complaintRepository,
        private ErrorHandlingService $errorHandler
    ) {}

    /**
     * Get filtered complaints
     */
    public function getFilteredComplaints(array $filters, int $perPage = 20)
    {
        try {
            return $this->complaintRepository->getFiltered($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error filtering complaints: ' . $e->getMessage());
            return $this->errorHandler->handleError($e, 'complaint.filter');
        }
    }

    /**
     * Get complaint statistics with caching
     */
    public function getComplaintStatistics(): array
    {
        return Cache::remember('complaint.statistics', 300, function () {
            try {
                return $this->complaintRepository->getStatistics();
            } catch (\Exception $e) {
                Log::error('Error getting complaint statistics: ' . $e->getMessage());
                return $this->errorHandler->handleError($e, 'complaint.statistics');
            }
        });
    }

    /**
     * Get resolution time metrics
     */
    public function getResolutionMetrics(): array
    {
        return Cache::remember('complaint.resolution_metrics', 600, function () {
            try {
                return $this->complaintRepository->getResolutionTimeMetrics();
            } catch (\Exception $e) {
                Log::error('Error getting resolution metrics: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get recent complaints
     */
    public function getRecentComplaints(int $limit = 10): array
    {
        return Cache::remember("complaint.recent_{$limit}", 180, function () use ($limit) {
            return $this->complaintRepository->getRecentComplaints($limit);
        });
    }

    /**
     * Get monthly complaint trends
     */
    public function getMonthlyTrends(): array
    {
        return Cache::remember('complaint.monthly_trends', 1800, function () {
            return $this->complaintRepository->getMonthlyComplaintCounts();
        });
    }

    /**
     * Get category distribution
     */
    public function getCategoryDistribution(): array
    {
        return Cache::remember('complaint.category_distribution', 900, function () {
            return $this->complaintRepository->getCategoryDistribution();
        });
    }

    /**
     * Get priority distribution
     */
    public function getPriorityDistribution(): array
    {
        return Cache::remember('complaint.priority_distribution', 900, function () {
            return $this->complaintRepository->getPriorityDistribution();
        });
    }

    /**
     * Create new complaint
     */
    public function createComplaint(array $data)
    {
        try {
            // Set default values
            $data['status'] = $data['status'] ?? 'pending';
            $data['priority'] = $data['priority'] ?? 'medium';
            $data['submitted_at'] = now();

            $complaint = $this->complaintRepository->create($data);
            
            // Clear related caches
            $this->clearComplaintCaches();
            
            Log::info('Complaint created', ['complaint_id' => $complaint->id]);
            
            return $complaint;
        } catch (\Exception $e) {
            Log::error('Error creating complaint: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update complaint status
     */
    public function updateComplaintStatus(int $complaintId, string $status, ?string $resolution = null): bool
    {
        try {
            $result = $this->complaintRepository->updateStatus($complaintId, $status, $resolution);
            
            if ($result) {
                // Clear related caches
                $this->clearComplaintCaches();
                
                Log::info('Complaint status updated', [
                    'complaint_id' => $complaintId,
                    'new_status' => $status,
                    'resolution' => $resolution ? 'provided' : 'none'
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error updating complaint status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get complaints by status
     */
    public function getComplaintsByStatus(string $status)
    {
        return Cache::remember("complaint.status_{$status}", 300, function () use ($status) {
            return $this->complaintRepository->getByStatus($status);
        });
    }

    /**
     * Get complaints by category
     */
    public function getComplaintsByCategory(string $category)
    {
        return Cache::remember("complaint.category_{$category}", 300, function () use ($category) {
            return $this->complaintRepository->getByCategory($category);
        });
    }

    /**
     * Get dashboard data for complaints
     */
    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->getComplaintStatistics(),
            'recent_complaints' => $this->getRecentComplaints(5),
            'resolution_metrics' => $this->getResolutionMetrics(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'category_distribution' => $this->getCategoryDistribution(),
            'priority_distribution' => $this->getPriorityDistribution(),
        ];
    }

    /**
     * Get complaints that need attention (high priority or overdue)
     */
    public function getComplaintsNeedingAttention(): array
    {
        $highPriorityComplaints = $this->getComplaintsByStatus('pending')
            ->filter(function ($complaint) {
                return $complaint->priority === 'high' || 
                       $complaint->created_at->diffInDays(now()) > 7;
            });

        return $this->formatComplaintsForAttention($highPriorityComplaints);
    }

    /**
     * Get complaint resolution performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $resolutionMetrics = $this->getResolutionMetrics();
        $statistics = $this->getComplaintStatistics();

        return [
            'avg_resolution_time' => $resolutionMetrics['avg_days'] ?? 0,
            'resolution_rate' => $statistics['total'] > 0 
                ? round(($statistics['resolved'] / $statistics['total']) * 100, 2) 
                : 0,
            'pending_complaints' => $statistics['pending'] ?? 0,
            'overdue_complaints' => $this->getOverdueComplaintsCount(),
        ];
    }

    /**
     * Get count of overdue complaints
     */
    private function getOverdueComplaintsCount(): int
    {
        return $this->getComplaintsByStatus('pending')
            ->filter(function ($complaint) {
                return $complaint->created_at->diffInDays(now()) > 7;
            })->count();
    }

    /**
     * Format complaints for attention display
     */
    private function formatComplaintsForAttention($complaints): array
    {
        return $complaints->map(function ($complaint) {
            return [
                'id' => $complaint->id,
                'subject' => $complaint->subject,
                'priority' => $complaint->priority,
                'days_old' => $complaint->created_at->diffInDays(now()),
                'resident_name' => $complaint->resident 
                    ? "{$complaint->resident->first_name} {$complaint->resident->last_name}"
                    : 'N/A',
                'category' => $complaint->category,
            ];
        })->toArray();
    }

    /**
     * Clear all complaint-related caches
     */
    private function clearComplaintCaches(): void
    {
        $cacheKeys = [
            'complaint.statistics',
            'complaint.resolution_metrics',
            'complaint.recent_10',
            'complaint.recent_5',
            'complaint.monthly_trends',
            'complaint.category_distribution',
            'complaint.priority_distribution',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear status-based caches
        $statuses = ['pending', 'in_progress', 'resolved', 'closed'];
        foreach ($statuses as $status) {
            Cache::forget("complaint.status_{$status}");
        }
    }
}