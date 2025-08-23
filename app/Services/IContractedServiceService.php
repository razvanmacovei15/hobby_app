<?php

namespace App\Services;

use App\Models\ContractedService;

interface IContractedServiceService
{
    public function getContractedServiceById(int $id): ContractedService;
}
