<?php

namespace App\Services;

use App\Models\WorkReport;

interface IWorkReportService
{
    public function createReportFromFilamentResource(array $data);
    public function getAllExecutorsForThisWorkspace();
    public function getContractIdFromWorkSpaceOwner(int $executorId): int;
    public function getAllServicesForThisContract(int $contractId);
    public function getServiceUnitOfMeasure(int $serviceId): ?string;
    public function getPricePerUnit(int $serviceId): ?string;
    
    public function markAsApproved(WorkReport $workReport, int $approvedBy): WorkReport;
}
