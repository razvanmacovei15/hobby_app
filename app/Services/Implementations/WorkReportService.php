<?php

namespace App\Services\Implementations;

use App\Enums\ServiceType;
use App\Models\ContractService;
use App\Models\ContractExtraService;
use App\Services\IWorkReportService;
use Illuminate\Support\Collection;

class WorkReportService implements IWorkReportService
{

    public function getServices()
    {

    }

    public function getExtraServices()
    {
        // TODO: Implement getExtraServices() method.
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
