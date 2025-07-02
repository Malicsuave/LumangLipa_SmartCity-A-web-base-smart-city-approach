<?php

namespace App\Repositories\Interfaces;

interface PopulationRepositoryInterface
{
    public function getPotentialDuplicates(): array;
    public function findResident(int $id);
    public function findFamilyMember(int $id);
    public function deleteResident(int $id): bool;
    public function deleteFamilyMember(int $id): bool;
    public function deleteFamilyMembers(array $ids): bool;
    public function updateResident(int $id, array $data): bool;
}