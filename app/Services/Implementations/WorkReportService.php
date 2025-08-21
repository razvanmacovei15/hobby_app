<?php

namespace App\Services\Implementations;

use App\Models\Contract;
use App\Models\ContractAnnex;
use App\Models\WorkReport;
use App\Models\Workspace;
use App\Services\IWorkReportService;
use Filament\Facades\Filament;
use InvalidArgumentException;
use RuntimeException;

class WorkReportService implements IWorkReportService
{

    public function createReportFromFilamentResource(array $data)
    {
        $workspace = Filament::getTenant();
        if (! $workspace) {
            throw new RuntimeException('No active workspace selected.');
        }

        $executorId = (int) $data['company_id'];

        $contractId = $this->getContractIdFromWorkSpaceOwner($executorId);

        // 4) Normalize / validate inputs
        $reportMonth = (int) ($data['report_month'] ?? 0);
        $reportYear  = (int) ($data['report_year'] ?? 0);

        if ($reportMonth < 1 || $reportMonth > 12) {
            throw new InvalidArgumentException('report_month must be between 1 and 12.');
        }
        if ($reportYear < 2000 || $reportYear > 2100) {
            throw new InvalidArgumentException('report_year looks invalid.');
        }

        // Prefer the provided author, fallback to current user
        $writtenBy = isset($data['written_by']) ? (int) $data['written_by'] : (int) auth()->id();

        // 5) Create the report (report_number is assigned in the model’s creating hook)
        //    Your model wraps the numbering in a transaction, so we can keep this simple.
        $workReport = WorkReport::create([
            'contract_id'   => $contractId,
            'company_id'    => $executorId, // the executor company on the report
            'written_by'    => $writtenBy,
            'report_month'  => $reportMonth,
            'report_year'   => $reportYear,
            'notes'         => $data['notes'] ?? null,
        ]);
        return $workReport->toArray();
    }

    public function getAllExecutorsForThisWorkspace()
    {
        $workspace = Filament::getTenant();
        if (! $workspace) {
            throw new RuntimeException('No active workspace selected.');
        }
        return $workspace->executors();
    }

    private function getContractIdFromWorkSpaceOwner(int $executorId): int
    {
        $workspace = Filament::getTenant();
        if (! $workspace) {
            throw new RuntimeException('No active workspace selected.');
        }

        $contract = Contract::query()
            ->where('executor_id', $executorId)
            ->where('beneficiary_id', $workspace->owner_id)
            ->first();

        if (! $contract) {
            throw new RuntimeException('No contract found for this workspace owner.');
        }

        return $contract->id;
    }
}
