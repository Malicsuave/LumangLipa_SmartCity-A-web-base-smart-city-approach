<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PopulationRepositoryInterface;
use App\Models\Resident;
use App\Models\FamilyMember;
use Illuminate\Support\Collection;

class PopulationRepository implements PopulationRepositoryInterface
{
    public function getPotentialDuplicates(): array
    {
        $duplicateResidents = $this->findDuplicateResidents();
        $duplicateFamilyMembers = $this->findDuplicateFamilyMembers();
        $residentFamilyDuplicates = $this->findResidentFamilyDuplicates();

        return [
            'duplicate_residents' => $duplicateResidents,
            'duplicate_family_members' => $duplicateFamilyMembers,
            'resident_family_duplicates' => $residentFamilyDuplicates
        ];
    }

    public function findResident(int $id): ?Resident
    {
        return Resident::find($id);
    }

    public function findFamilyMember(int $id): ?FamilyMember
    {
        return FamilyMember::find($id);
    }

    public function deleteResident(int $id): bool
    {
        $resident = $this->findResident($id);
        return $resident ? $resident->delete() : false;
    }

    public function deleteFamilyMember(int $id): bool
    {
        $familyMember = $this->findFamilyMember($id);
        return $familyMember ? $familyMember->delete() : false;
    }

    public function deleteFamilyMembers(array $ids): bool
    {
        return FamilyMember::whereIn('id', $ids)->delete() > 0;
    }

    public function updateResident(int $id, array $data): bool
    {
        $resident = $this->findResident($id);
        if (!$resident) {
            return false;
        }

        return $resident->update($data);
    }

    private function findDuplicateResidents(): Collection
    {
        return Resident::selectRaw('first_name, last_name, date_of_birth, COUNT(*) as count')
            ->groupBy('first_name', 'last_name', 'date_of_birth')
            ->having('count', '>', 1)
            ->with('duplicateGroup')
            ->get();
    }

    private function findDuplicateFamilyMembers(): Collection
    {
        return FamilyMember::selectRaw('name, date_of_birth, household_id, COUNT(*) as count')
            ->groupBy('name', 'date_of_birth', 'household_id')
            ->having('count', '>', 1)
            ->with('duplicateGroup')
            ->get();
    }

    private function findResidentFamilyDuplicates(): Collection
    {
        return Resident::whereExists(function ($query) {
            $query->select('id')
                ->from('family_members')
                ->whereRaw('LOWER(family_members.name) = LOWER(CONCAT(residents.first_name, " ", residents.last_name))')
                ->whereRaw('family_members.date_of_birth = residents.date_of_birth');
        })->get();
    }
}