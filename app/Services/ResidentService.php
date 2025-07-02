<?php

namespace App\Services;

use App\Repositories\Contracts\ResidentRepositoryInterface;
use App\Services\ErrorHandlingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ResidentService
{
    public function __construct(
        private ResidentRepositoryInterface $residentRepository,
        private ErrorHandlingService $errorHandler
    ) {}

    /**
     * Get filtered and paginated residents
     */
    public function getFilteredResidents(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        try {
            return $this->residentRepository->getFiltered($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error filtering residents: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get resident statistics with caching
     */
    public function getResidentStatistics(): array
    {
        return Cache::remember('resident.statistics', 300, function () {
            try {
                return $this->residentRepository->getStatistics();
            } catch (\Exception $e) {
                Log::error('Error getting resident statistics: ' . $e->getMessage());
                return $this->errorHandler->handleError($e, 'resident.statistics');
            }
        });
    }

    /**
     * Get age distribution with caching
     */
    public function getAgeDistribution(): array
    {
        return Cache::remember('resident.age_distribution', 600, function () {
            return $this->residentRepository->getAgeDistribution();
        });
    }

    /**
     * Get gender distribution with caching
     */
    public function getGenderDistribution(): array
    {
        return Cache::remember('resident.gender_distribution', 600, function () {
            return $this->residentRepository->getGenderDistribution();
        });
    }

    /**
     * Create or update resident with validation
     */
    public function createOrUpdateResident(array $data, ?int $residentId = null)
    {
        return DB::transaction(function () use ($data, $residentId) {
            try {
                if ($residentId) {
                    $resident = $this->residentRepository->update($residentId, $data);
                    Log::info('Resident updated', ['resident_id' => $residentId]);
                } else {
                    $resident = $this->residentRepository->create($data);
                    Log::info('Resident created', ['resident_id' => $resident->id]);
                }

                // Clear related caches
                $this->clearResidentCaches();

                return $resident;

            } catch (\Exception $e) {
                Log::error('Error creating/updating resident: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Get residents by household
     */
    public function getResidentsByHousehold(int $householdId)
    {
        return $this->residentRepository->getByHousehold($householdId);
    }

    /**
     * Search residents for autocomplete
     */
    public function searchResidentsForAutocomplete(string $query, int $limit = 10): array
    {
        return $this->residentRepository->searchForAutocomplete($query, $limit);
    }

    /**
     * Find resident by barangay ID
     */
    public function findByBarangayId(string $barangayId)
    {
        return $this->residentRepository->findByBarangayId($barangayId);
    }

    /**
     * Get residents by age group
     */
    public function getResidentsByAgeGroup(string $ageGroup)
    {
        return Cache::remember("resident.age_group_{$ageGroup}", 300, function () use ($ageGroup) {
            return $this->residentRepository->getByAgeGroup($ageGroup);
        });
    }

    /**
     * Get residents by civil status
     */
    public function getResidentsByCivilStatus(string $civilStatus)
    {
        return Cache::remember("resident.civil_status_{$civilStatus}", 300, function () use ($civilStatus) {
            return $this->residentRepository->getByCivilStatus($civilStatus);
        });
    }

    /**
     * Get residents by type
     */
    public function getResidentsByType(string $type)
    {
        return Cache::remember("resident.type_{$type}", 300, function () use ($type) {
            return $this->residentRepository->getByResidentType($type);
        });
    }

    /**
     * Get dashboard data for residents
     */
    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->getResidentStatistics(),
            'age_distribution' => $this->getAgeDistribution(),
            'gender_distribution' => $this->getGenderDistribution(),
            'recent_registrations' => $this->getRecentRegistrations(5),
            'population_trends' => $this->getPopulationTrends(),
        ];
    }

    /**
     * Get recent registrations
     */
    public function getRecentRegistrations(int $limit = 10): array
    {
        return Cache::remember("resident.recent_{$limit}", 180, function () use ($limit) {
            // This would need to be added to the repository interface if needed
            // For now, let's use a simple approach
            return [];
        });
    }

    /**
     * Get population trends (monthly growth)
     */
    public function getPopulationTrends(): array
    {
        return Cache::remember('resident.population_trends', 1800, function () {
            // This would also need repository method
            // For now, basic implementation
            return [
                'current_month' => $this->residentRepository->getNewResidentsThisMonth(),
                'growth_rate' => 0, // Would calculate based on historical data
            ];
        });
    }

    /**
     * Delete resident
     */
    public function deleteResident(int $residentId): bool
    {
        try {
            $result = $this->residentRepository->delete($residentId);
            
            if ($result) {
                $this->clearResidentCaches();
                Log::info('Resident deleted', ['resident_id' => $residentId]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error deleting resident: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get resident demographics summary
     */
    public function getDemographicsSummary(): array
    {
        return [
            'total_population' => $this->getResidentStatistics()['total'],
            'age_breakdown' => $this->getAgeDistribution(),
            'gender_breakdown' => $this->getGenderDistribution(),
            'civil_status_breakdown' => $this->getCivilStatusBreakdown(),
            'resident_type_breakdown' => $this->getResidentTypeBreakdown(),
        ];
    }

    /**
     * Get civil status breakdown
     */
    private function getCivilStatusBreakdown(): array
    {
        return Cache::remember('resident.civil_status_breakdown', 600, function () {
            // This would need a repository method to get all civil status counts
            // For now, return empty array
            return [];
        });
    }

    /**
     * Get resident type breakdown
     */
    private function getResidentTypeBreakdown(): array
    {
        return Cache::remember('resident.type_breakdown', 600, function () {
            // This would need a repository method to get all type counts
            // For now, return empty array
            return [];
        });
    }

    /**
     * Clear all resident-related caches
     */
    private function clearResidentCaches(): void
    {
        $cacheKeys = [
            'resident.statistics',
            'resident.age_distribution',
            'resident.gender_distribution',
            'resident.recent_10',
            'resident.recent_5',
            'resident.population_trends',
            'resident.civil_status_breakdown',
            'resident.type_breakdown',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Clear age group caches
        $ageGroups = ['children', 'adults', 'seniors'];
        foreach ($ageGroups as $group) {
            Cache::forget("resident.age_group_{$group}");
        }
    }
}