<?php

namespace App\Services;

use App\Repositories\Interfaces\PopulationRepositoryInterface;
use App\Exceptions\PopulationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PopulationService
{
    public function __construct(
        private PopulationRepositoryInterface $populationRepository
    ) {}

    public function getDuplicates(): array
    {
        try {
            return $this->populationRepository->getPotentialDuplicates();
        } catch (\Exception $e) {
            Log::error('Error fetching duplicates: ' . $e->getMessage());
            throw new PopulationException('Unable to fetch duplicate records');
        }
    }

    public function mergeDuplicate(string $type, string $action, array $data): bool
    {
        return DB::transaction(function () use ($type, $action, $data) {
            try {
                switch ($type) {
                    case 'resident_family_member':
                        return $this->mergeResidentFamilyMember($action, $data);
                    case 'duplicate_family_members':
                        return $this->mergeDuplicateFamilyMembers($data);
                    default:
                        throw new PopulationException('Invalid merge type');
                }
            } catch (\Exception $e) {
                Log::error('Error merging duplicates: ' . $e->getMessage());
                throw new PopulationException('Failed to merge duplicate records: ' . $e->getMessage());
            }
        });
    }

    public function removeDuplicate(string $type, int $id): bool
    {
        try {
            return match ($type) {
                'resident' => $this->populationRepository->deleteResident($id),
                'family_member' => $this->populationRepository->deleteFamilyMember($id),
                default => throw new PopulationException('Invalid duplicate type')
            };
        } catch (\Exception $e) {
            Log::error("Error removing {$type} duplicate {$id}: " . $e->getMessage());
            throw new PopulationException("Failed to remove {$type} record");
        }
    }

    private function mergeResidentFamilyMember(string $action, array $data): bool
    {
        $residentId = $data['resident_id'] ?? null;
        $familyMemberId = $data['family_member_id'] ?? null;

        if (!$residentId || !$familyMemberId) {
            throw new PopulationException('Missing resident or family member ID');
        }

        $resident = $this->populationRepository->findResident($residentId);
        $familyMember = $this->populationRepository->findFamilyMember($familyMemberId);

        if (!$resident || !$familyMember) {
            throw new PopulationException('Resident or family member not found');
        }

        return match ($action) {
            'keep_resident' => $this->populationRepository->deleteFamilyMember($familyMemberId),
            'promote_family_member' => $this->promoteFamilyMemberToResident($resident, $familyMember),
            'merge_data' => $this->mergeDataFromFamilyMember($resident, $familyMember),
            default => throw new PopulationException('Invalid merge action')
        };
    }

    private function mergeDuplicateFamilyMembers(array $data): bool
    {
        $primaryId = $data['primary_id'] ?? null;
        $duplicateIds = $data['duplicate_ids'] ?? [];

        if (!$primaryId || empty($duplicateIds)) {
            throw new PopulationException('Missing primary ID or duplicate IDs');
        }

        $primaryMember = $this->populationRepository->findFamilyMember($primaryId);
        if (!$primaryMember) {
            throw new PopulationException('Primary family member not found');
        }

        return $this->populationRepository->deleteFamilyMembers($duplicateIds);
    }

    private function promoteFamilyMemberToResident($resident, $familyMember): bool
    {
        // For now, just remove the family member duplicate
        // In a full implementation, this would involve complex business logic
        return $this->populationRepository->deleteFamilyMember($familyMember->id);
    }

    private function mergeDataFromFamilyMember($resident, $familyMember): bool
    {
        $updateData = [];

        // Merge contact information if missing from resident
        if (!$resident->contact_number && $familyMember->phone) {
            $updateData['contact_number'] = $familyMember->phone;
        }

        // Add other merge logic as needed
        if (!empty($updateData)) {
            $this->populationRepository->updateResident($resident->id, $updateData);
        }

        return $this->populationRepository->deleteFamilyMember($familyMember->id);
    }
}