<?php

namespace App\Repositories\Contracts;

interface ResidentRepositoryInterface
{
    public function getFiltered(array $filters, int $perPage = 20);
    public function getStatistics(): array;
    public function findByBarangayId(string $barangayId);
    public function getByHousehold(int $householdId);
    public function searchForAutocomplete(string $query, int $limit = 10): array;
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function getAgeDistribution(): array;
    public function getGenderDistribution(): array;
    public function getNewResidentsThisMonth(): int;
    public function getByAgeGroup(string $ageGroup);
    public function getByCivilStatus(string $civilStatus);
    public function getByResidentType(string $type);
}