<?php

namespace App\Services\Implementations;

use App\Services\IWorkReportService;

class WorkReportService implements IWorkReportService
{

    public function createReportFromFilamentResource(array $data)
    {
        // TODO: Implement createReportFromFilamentResource() method.

        // from company_id get the relevant contract with the workspace owner
    }

    public function getAllExecutorsForThisWorkspace()
    {
        // TODO: Implement getAllExecutorsForThisWorkspace() method.
    }

    private function getContractIdFromWorkSpaceOwner()
    {

    }

    private function getContractAnnexIdFromContract()
    {

    }
}
