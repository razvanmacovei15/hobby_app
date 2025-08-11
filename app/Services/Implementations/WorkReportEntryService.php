<?php

namespace App\Services\Implementations;

use App\Enums\ServiceType;
use App\Models\ContractService;
use App\Models\ContractExtraService;
use App\Services\IWorkReportEntryService;
use Illuminate\Support\Collection;

class WorkReportEntryService implements IWorkReportEntryService
{

    public function getServices(ServiceType $serviceType, int $companyId, ?int $contractId = null): Collection
    {
        return match($serviceType) {
            ServiceType::CONTRACT_SERVICE => $this->getContractServices($companyId, $contractId),
            ServiceType::CONTRACT_EXTRA_SERVICE => $this->getContractExtraServices($companyId, $contractId),
        };
    }

    private function getContractServices(int $companyId, ?int $contractId = null): Collection
    {
        $query = ContractService::query()
            ->with(['contractAnnex.contract'])
            ->whereHas('contractAnnex.contract', function ($query) use ($companyId) {
                $query->where('executor_id', $companyId);
            });

        if ($contractId) {
            $query->whereHas('contractAnnex.contract', function ($query) use ($contractId) {
                $query->where('id', $contractId);
            });
        }

        return $query->get();
    }

    private function getContractExtraServices(int $companyId, ?int $contractId = null): Collection
    {
        $query = ContractExtraService::query()
            ->where('company_id', $companyId);

        if ($contractId) {
            $query->where('contract_id', $contractId);
        }

        return $query->get();
    }
}
