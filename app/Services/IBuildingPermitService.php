<?php

namespace App\Services;

use App\Models\BuildingPermit;
use Illuminate\Support\Collection;

interface IBuildingPermitService
{
    public function getAllPermitsForCompanyId(int $companyId): Collection;
    
    public function createPermit(array $data): BuildingPermit;
    
    public function updatePermit(BuildingPermit $permit, array $data): BuildingPermit;
    
    public function deletePermit(BuildingPermit $permit): bool;
    
    public function getPermitsByStatus(string $status): Collection;
    
    public function getPermitsExpiringWithin(int $days): Collection;
    
    public function calculateWorkEndDate(BuildingPermit $permit): ?string;
    
    public function getPermitsByWorkspace(int $workspaceId): Collection;
    
    public function mutateFormDataBeforeCreate(array $data): array;
    
    public function mutateFormDataBeforeUpdate(BuildingPermit $permit, array $data): array;
}
