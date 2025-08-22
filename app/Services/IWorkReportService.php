<?php

namespace App\Services;

interface IWorkReportService
{
    public function createReportFromFilamentResource(array $data);
    public function getAllExecutorsForThisWorkspace();
    public function getContractIdFromWorkSpaceOwner(int $executorId): int;
    public function getAllServicesForThisContract(int $contractId);
}
