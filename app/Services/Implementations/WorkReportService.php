<?php

namespace App\Services\Implementations;

use App\Models\Contract;
use App\Models\ContractAnnex;
use App\Models\WorkReport;
use App\Models\Workspace;
use App\Services\IWorkReportService;
use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use RuntimeException;

class WorkReportService implements IWorkReportService
{

    public function createReportFromFilamentResource(array $data)
    {
        $workspace = Filament::getTenant();
        if (!$workspace) {
            throw new RuntimeException('No active workspace selected.');
        }

        $executorId = (int)$data['company_id'];

        $contractId = $this->getContractIdFromWorkSpaceOwner($executorId);

        // 4) Normalize / validate inputs
        $reportMonth = (int)($data['report_month'] ?? 0);
        $reportYear = (int)($data['report_year'] ?? 0);

        if ($reportMonth < 1 || $reportMonth > 12) {
            throw new InvalidArgumentException('report_month must be between 1 and 12.');
        }
        if ($reportYear < 2000 || $reportYear > 2100) {
            throw new InvalidArgumentException('report_year looks invalid.');
        }

        // Prefer the provided author, fallback to current user
        $writtenBy = (int)auth()->id();

        return [
            'contract_id' => $contractId,
            'company_id' => $executorId,
            'written_by' => $writtenBy,
            'report_month' => $reportMonth,
            'report_year' => $reportYear,
            'notes' => $data['notes'] ?? null,
        ];
    }

    public function getAllExecutorsForThisWorkspace()
    {
        $workspace = Filament::getTenant();
        if (!$workspace) {
            throw new RuntimeException('No active workspace selected.');
        }

        // Get the executor company IDs from the workspace
        return $workspace->executors()->pluck('executor_id');
    }

    public function getContractIdFromWorkSpaceOwner(int $executorId): int
    {
        $workspace = Filament::getTenant();
        if (!$workspace) {
            throw new RuntimeException('No active workspace selected.');
        }

        $contract = Contract::query()
            ->where('executor_id', $executorId)
            ->where('beneficiary_id', $workspace->owner_id)
            ->first();

        if (!$contract) {
            throw new RuntimeException('No contract found for this workspace owner.');
        }

        return $contract->id;
    }

    public function getAllServicesForThisContract(int $contractId)
    {
        // 1) Get all annex IDs for the contract
        $annexIds = ContractAnnex::query()
            ->where('contract_id', $contractId)
            ->pluck('id');

        // 2) Get services with proper id => name mapping
        $services = \App\Models\ContractService::query()
            ->whereIn('contract_annex_id', $annexIds)
            ->orderBy('sort_order')
            ->pluck('name', 'id');

        return $services;
    }

    public function getServiceUnitOfMeasure(int $serviceId): ?string
    {
        return \App\Models\ContractService::query()
            ->where('id', $serviceId)
            ->value('unit_of_measure');
    }

    public function getPricePerUnit(int $serviceId): ?string
    {
        return \App\Models\ContractService::query()
            ->where('id', $serviceId)
            ->value('price_per_unit_of_measure');
    }
}
