<?php

namespace App\Services\Implementations;

use App\Models\BuildingPermit;
use App\Services\IBuildingPermitService;
use Illuminate\Support\Collection;

class BuildingPermitService implements IBuildingPermitService
{
    public function getAllPermitsForCompanyId(int $companyId): Collection
    {
        return BuildingPermit::query()
            ->whereHas('workspace', function ($query) use ($companyId) {
                $query->where('owner_id', $companyId);
            })
            ->with(['workspace'])
            ->get();
    }
}
