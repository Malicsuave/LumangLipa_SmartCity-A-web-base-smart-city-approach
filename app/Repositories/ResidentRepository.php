<?php

namespace App\Repositories;

use App\Models\Resident;
use App\Repositories\Contracts\ResidentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResidentRepository implements ResidentRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20)
    {
        $query = Resident::query();

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('barangay_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Type filter
        if (!empty($filters['type'])) {
            $query->where('type_of_resident', $filters['type']);
        }

        // Civil status filter
        if (!empty($filters['civil_status'])) {
            $query->where('civil_status', $filters['civil_status']);
        }

        // Gender filter
        if (!empty($filters['gender'])) {
            $query->where('sex', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->applyAgeGroupFilter($query, $filters['age_group']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('last_name', 'asc')
                    ->orderBy('first_name', 'asc')
                    ->paginate($perPage);
    }

    public function getStatistics(): array
    {
        return [
            'total' => Resident::count(),
            'male' => Resident::where('sex', 'Male')->count(),
            'female' => Resident::where('sex', 'Female')->count(),
            'children' => $this->getChildrenCount(),
            'adults' => $this->getAdultsCount(),
            'seniors' => $this->getSeniorsCount(),
            'new_this_month' => $this->getNewResidentsThisMonth(),
            'active' => Resident::where('status', 'active')->count(),
            'inactive' => Resident::where('status', 'inactive')->count(),
        ];
    }

    public function findByBarangayId(string $barangayId)
    {
        return Resident::where('barangay_id', $barangayId)->first();
    }

    public function getByHousehold(int $householdId)
    {
        return Resident::where('household_id', $householdId)
                      ->orderBy('birthdate', 'asc')
                      ->get();
    }

    public function searchForAutocomplete(string $query, int $limit = 10): array
    {
        return Resident::where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('barangay_id', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get(['id', 'first_name', 'last_name', 'barangay_id'])
                ->map(function ($resident) {
                    return [
                        'id' => $resident->id,
                        'name' => "{$resident->first_name} {$resident->last_name}",
                        'barangay_id' => $resident->barangay_id,
                    ];
                })
                ->toArray();
    }

    public function create(array $data)
    {
        return Resident::create($data);
    }

    public function update(int $id, array $data)
    {
        $resident = Resident::findOrFail($id);
        $resident->update($data);
        return $resident->fresh();
    }

    public function delete(int $id): bool
    {
        return Resident::destroy($id) > 0;
    }

    public function getAgeDistribution(): array
    {
        return [
            'children' => $this->getChildrenCount(),
            'adults' => $this->getAdultsCount(),
            'seniors' => $this->getSeniorsCount(),
        ];
    }

    public function getGenderDistribution(): array
    {
        return Resident::select('sex', DB::raw('COUNT(*) as count'))
                      ->groupBy('sex')
                      ->pluck('count', 'sex')
                      ->toArray();
    }

    public function getNewResidentsThisMonth(): int
    {
        return Resident::whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year)
                      ->count();
    }

    public function getByAgeGroup(string $ageGroup)
    {
        $query = Resident::query();
        $this->applyAgeGroupFilter($query, $ageGroup);
        return $query->get();
    }

    public function getByCivilStatus(string $civilStatus)
    {
        return Resident::where('civil_status', $civilStatus)->get();
    }

    public function getByResidentType(string $type)
    {
        return Resident::where('type_of_resident', $type)->get();
    }

    /**
     * Apply age group filter to query
     */
    private function applyAgeGroupFilter($query, string $ageGroup): void
    {
        $now = Carbon::now();
        
        switch ($ageGroup) {
            case 'children':
                $query->whereDate('birthdate', '>=', $now->copy()->subYears(18));
                break;
            case 'adults':
                $query->whereDate('birthdate', '<', $now->copy()->subYears(18))
                      ->whereDate('birthdate', '>=', $now->copy()->subYears(60));
                break;
            case 'seniors':
                $query->whereDate('birthdate', '<', $now->copy()->subYears(60));
                break;
        }
    }

    /**
     * Get children count (under 18)
     */
    private function getChildrenCount(): int
    {
        return Resident::whereDate('birthdate', '>=', Carbon::now()->subYears(18))->count();
    }

    /**
     * Get adults count (18-59)
     */
    private function getAdultsCount(): int
    {
        $now = Carbon::now();
        return Resident::whereDate('birthdate', '<', $now->copy()->subYears(18))
                      ->whereDate('birthdate', '>=', $now->copy()->subYears(60))
                      ->count();
    }

    /**
     * Get seniors count (60+)
     */
    private function getSeniorsCount(): int
    {
        return Resident::whereDate('birthdate', '<', Carbon::now()->subYears(60))->count();
    }
}