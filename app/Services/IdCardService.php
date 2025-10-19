<?php

namespace App\Services;

use App\Models\Resident;
use App\Services\ErrorHandlingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IdCardService
{
    public function __construct(
        private ErrorHandlingService $errorHandler
    ) {}

    /**
     * Get filtered residents for ID management
     */
    public function getFilteredResidentsForIds(array $filters, int $perPage = 10): array
    {
        try {
            $baseQuery = Resident::query();
            
            $this->applyIdFilters($baseQuery, $filters);
            
            return [
                'pending_issuance' => $this->getPendingIssuance($baseQuery, $perPage),
                'pending_renewal' => $this->getPendingRenewal($baseQuery, $perPage),
                'expiring_soon' => $this->getExpiringSoon($baseQuery, $perPage),
                'ready_for_pickup' => $this->getReadyForPickup($baseQuery, $perPage),
            ];
        } catch (\Exception $e) {
            Log::error('Error filtering residents for IDs: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Apply ID-specific filters to query
     */
    private function applyIdFilters($query, array $filters): void
    {
        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('barangay_id', 'like', "%{$search}%");
            });
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->applyAgeGroupFilter($query, $filters['age_group']);
        }

        // Photo status filter
        if (!empty($filters['has_photo'])) {
            if ($filters['has_photo'] === 'yes') {
                $query->whereNotNull('photo');
            } else {
                $query->whereNull('photo');
            }
        }

        // Status filter
        if (!empty($filters['id_status'])) {
            $this->applyStatusFilter($query, $filters['id_status']);
        }
    }

    /**
     * Apply age group filter
     */
    private function applyAgeGroupFilter($query, string $ageGroup): void
    {
        $today = Carbon::today();
        
        switch ($ageGroup) {
            case '0-17':
                $query->whereDate('birthdate', '>', $today->copy()->subYears(18));
                break;
            case '18-59':
                $query->whereDate('birthdate', '<=', $today->copy()->subYears(18))
                      ->whereDate('birthdate', '>', $today->copy()->subYears(60));
                break;
            case '60+':
                $query->whereDate('birthdate', '<=', $today->copy()->subYears(60));
                break;
        }
    }

    /**
     * Apply status filter
     */
    private function applyStatusFilter($query, string $status): void
    {
        switch ($status) {
            case 'issued':
                $query->where('id_status', 'issued');
                break;
            case 'not_issued':
                $query->where(function($q) {
                    $q->whereNull('id_status')->orWhere('id_status', '!=', 'issued');
                });
                break;
            case 'needs_renewal':
                $query->where('id_status', 'needs_renewal');
                break;
            case 'expired':
                $query->where(function($q) {
                    $q->where('id_status', 'expired')
                      ->orWhere(function($subQ) {
                          $subQ->where('id_status', 'issued')
                               ->whereNotNull('id_expires_at')
                               ->where('id_expires_at', '<', Carbon::now());
                      });
                });
                break;
            case 'ready':
                $query->where('id_status', 'ready_for_pickup');
                break;
        }
    }

    /**
     * Get residents with pending ID issuance
     */
    private function getPendingIssuance($baseQuery, int $perPage)
    {
        $query = clone $baseQuery;
        return $query->where(function($q) {
                    $q->whereNull('id_status')
                      ->orWhere('id_status', 'pending')
                      ->orWhere('id_status', 'processing');
                })
                ->paginate($perPage, ['*'], 'issuance_page');
    }

    /**
     * Get residents with pending ID renewal
     */
    private function getPendingRenewal($baseQuery, int $perPage)
    {
        $query = clone $baseQuery;
        return $query->where('id_status', 'needs_renewal')
                    ->paginate($perPage, ['*'], 'renewal_page');
    }

    /**
     * Get IDs expiring soon
     */
    private function getExpiringSoon($baseQuery, int $perPage)
    {
        $query = clone $baseQuery;
        return $query->where('id_status', 'issued')
                    ->whereNotNull('id_expires_at')
                    ->where('id_expires_at', '<=', Carbon::now()->addMonths(3))
                    ->where('id_expires_at', '>=', Carbon::now())
                    ->paginate($perPage, ['*'], 'expiring_page');
    }

    /**
     * Get IDs ready for pickup
     */
    private function getReadyForPickup($baseQuery, int $perPage)
    {
        $query = clone $baseQuery;
        return $query->where('id_status', 'ready_for_pickup')
                    ->paginate($perPage, ['*'], 'pickup_page');
    }

    /**
     * Get ID statistics
     */
    public function getIdStatistics(): array
    {
        try {
            return [
                'total_ids' => Resident::whereNotNull('id_status')->count(),
                'issued_ids' => Resident::where('id_status', 'issued')->count(),
                'pending_ids' => Resident::where('id_status', 'pending')->count(),
                'renewal_ids' => Resident::where('id_status', 'needs_renewal')->count(),
                'ready_pickup' => Resident::where('id_status', 'ready_for_pickup')->count(),
                'expiring_soon' => $this->getExpiringSoonCount(),
            ];
        } catch (\Exception $e) {
            return $this->errorHandler->handleError($e, 'id_card.statistics');
        }
    }

    /**
     * Get count of IDs expiring soon
     */
    private function getExpiringSoonCount(): int
    {
        return Resident::where('id_status', 'issued')
                      ->whereNotNull('id_expires_at')
                      ->where('id_expires_at', '<=', Carbon::now()->addMonths(3))
                      ->where('id_expires_at', '>=', Carbon::now())
                      ->count();
    }

    /**
     * Update ID status
     */
    public function updateIdStatus(int $residentId, string $status, array $additionalData = []): bool
    {
        return DB::transaction(function () use ($residentId, $status, $additionalData) {
            try {
                $resident = Resident::findOrFail($residentId);
                
                $updateData = array_merge(['id_status' => $status], $additionalData);
                
                // Set appropriate timestamps based on status
                switch ($status) {
                    case 'issued':
                        $updateData['id_issued_at'] = now();
                        $updateData['id_expires_at'] = now()->addYears(3);
                        break;
                    case 'ready_for_pickup':
                        $updateData['id_ready_at'] = now();
                        break;
                    case 'picked_up':
                        $updateData['id_picked_up_at'] = now();
                        break;
                }
                
                $resident->update($updateData);
                
                Log::info('ID status updated', [
                    'resident_id' => $residentId,
                    'new_status' => $status,
                    'updated_by' => auth()->id()
                ]);
                
                return true;
                
            } catch (\Exception $e) {
                Log::error('Error updating ID status: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Generate QR code data for resident
     */
    public function generateQrCodeData(Resident $resident): string
    {
        $data = [
            'id' => $resident->barangay_id,
            'name' => trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}"),
            'address' => $resident->address,
            'issued' => now()->format('Y-m-d'),
            'expires' => now()->addYears(3)->format('Y-m-d'),
        ];
        
        return json_encode($data);
    }
}