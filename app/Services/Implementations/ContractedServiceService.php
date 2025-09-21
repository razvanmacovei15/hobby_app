<?php

namespace App\Services\Implementations;

use App\Models\ContractedService;
use App\Services\IContractedServiceService;

class ContractedServiceService implements IContractedServiceService
{

    public function getContractedServiceById(int $id): ContractedService
    {
        $contractedService = ContractedService::findOrFail($id);

        return $contractedService;
    }
}
