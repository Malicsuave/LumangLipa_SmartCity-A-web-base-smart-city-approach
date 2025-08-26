<?php

namespace App\Services;

use App\Repositories\Contracts\HealthServiceRepositoryInterface;
use App\Services\ErrorHandlingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnhancedHealthServiceService
{
    public function __construct(
        private HealthServiceRepositoryInterface $healthServiceRepository,
        private ErrorHandlingService $errorHandler
    ) {}

    /**
     * Get filtered health service requests
     */
    public function getFilteredHealthServices(array $filters, int $perPage = 20)
    {
        try {
            return $this->healthServiceRepository->getFiltered($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error filtering health services: ' . $e->getMessage());
            return $this->errorHandler->handleError($e, 'health_service.filter');
        }
    }

    /**
     * Get health service statistics with caching
     */
    public function getHealthServiceStatistics(): array
    {
        return Cache::remember('health_service.statistics', 300, function () {
            try {
                return $this->healthServiceRepository->getStatistics();
            } catch (\Exception $e) {
                Log::error('Error getting health service statistics: ' . $e->getMessage());
                return $this->errorHandler->handleError($e, 'health_service.statistics');
            }
        });
    }

    /**
     * Get service metrics with caching
     */
    public function getServiceMetrics(): array
    {
        return Cache::remember('health_service.metrics', 600, function () {
            try {
                return $this->healthServiceRepository->getServiceMetrics();
            } catch (\Exception $e) {
                Log::error('Error getting service metrics: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get recent health service requests
     */
    public function getRecentRequests(int $limit = 10)
    {
        return Cache::remember("health_service.recent_{$limit}", 180, function () use ($limit) {
            return $this->healthServiceRepository->getRecentRequests($limit);
        });
    }

    /**
     * Get monthly service trends
     */
    public function getMonthlyTrends(): array
    {
        return Cache::remember('health_service.monthly_trends', 1800, function () {
            return $this->healthServiceRepository->getMonthlyServiceCounts();
        });
    }

    /**
     * Get service type distribution
     */
    public function getServiceTypeDistribution(): array
    {
        return Cache::remember('health_service.type_distribution', 900, function () {
            return $this->healthServiceRepository->getServiceTypeDistribution();
        });
    }

    /**
     * Get patient age group analysis
     */
    public function getPatientAgeGroups(): array
    {
        return Cache::remember('health_service.age_groups', 600, function () {
            return $this->healthServiceRepository->getPatientAgeGroups();
        });
    }

    /**
     * Create new health service request
     */
    public function createHealthServiceRequest(array $data)
    {
        try {
            // Set default values
            $data['status'] = $data['status'] ?? 'pending';
            $data['priority'] = $data['priority'] ?? 'normal';
            $data['requested_at'] = now();

            $healthService = $this->healthServiceRepository->create($data);
            
            // Clear related caches
            $this->clearHealthServiceCaches();
            
            Log::info('Health service request created', ['request_id' => $healthService->id]);
            
            return $healthService;
        } catch (\Exception $e) {
            Log::error('Error creating health service request: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update health service status
     */
    public function updateHealthServiceStatus(int $requestId, string $status, ?array $serviceData = null): bool
    {
        try {
            $result = $this->healthServiceRepository->updateStatus($requestId, $status, $serviceData);
            
            if ($result) {
                // Clear related caches
                $this->clearHealthServiceCaches();
                
                Log::info('Health service status updated', [
                    'request_id' => $requestId,
                    'new_status' => $status,
                    'has_service_data' => !empty($serviceData)
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error updating health service status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get health services by status
     */
    public function getHealthServicesByStatus(string $status)
    {
        return Cache::remember("health_service.status_{$status}", 300, function () use ($status) {
            return $this->healthServiceRepository->getByStatus($status);
        });
    }

    /**
     * Get health services by type
     */
    public function getHealthServicesByType(string $serviceType)
    {
        return Cache::remember("health_service.type_{$serviceType}", 300, function () use ($serviceType) {
            return $this->healthServiceRepository->getByServiceType($serviceType);
        });
    }

    /**
     * Get dashboard data for health services
     */
    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->getHealthServiceStatistics(),
            'recent_requests' => $this->getRecentRequests(5),
            'service_metrics' => $this->getServiceMetrics(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'service_types' => $this->getServiceTypeDistribution(),
            'patient_demographics' => $this->getPatientAgeGroups(),
        ];
    }

    /**
     * Get services requiring attention (urgent or overdue)
     */
    public function getServicesNeedingAttention(): array
    {
        $urgentServices = $this->getHealthServicesByStatus('pending')
            ->filter(function ($service) {
                return $service->priority === 'urgent' || 
                       $service->created_at->diffInDays(now()) > 3;
            });

        return $this->formatServicesForAttention($urgentServices);
    }

    /**
     * Get service performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $serviceMetrics = $this->getServiceMetrics();
        $statistics = $this->getHealthServiceStatistics();

        return [
            'avg_completion_time' => $serviceMetrics['avg_completion_days'] ?? 0,
            'completion_rate' => $statistics['total'] > 0 
                ? round(($statistics['completed'] / $statistics['total']) * 100, 2) 
                : 0,
            'pending_services' => $statistics['pending'] ?? 0,
            'overdue_services' => $this->getOverdueServicesCount(),
        ];
    }

    /**
     * Get count of overdue services
     */
    private function getOverdueServicesCount(): int
    {
        return $this->getHealthServicesByStatus('pending')
            ->filter(function ($service) {
                return $service->created_at->diffInDays(now()) > 3;
            })->count();
    }

    /**
     * Format services for attention display
     */
    private function formatServicesForAttention($services): array
    {
        return $services->map(function ($service) {
            return [
                'id' => $service->id,
                'service_type' => $service->service_type,
                'priority' => $service->priority,
                'days_old' => $service->created_at->diffInDays(now()),
                'patient_name' => $service->resident 
                    ? "{$service->resident->first_name} {$service->resident->last_name}"
                    : 'N/A',
                'status' => $service->status,
            ];
        })->toArray();
    }

    /**
     * Clear all health service-related caches
     */
    private function clearHealthServiceCaches(): void
    {
        $cacheKeys = [
            'health_service.statistics',
            'health_service.metrics',
            'health_service.recent_10',
            'health_service.recent_5',
            'health_service.monthly_trends',
            'health_service.type_distribution',
            'health_service.age_groups',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear status-based caches
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        foreach ($statuses as $status) {
            Cache::forget("health_service.status_{$status}");
        }
    }
}