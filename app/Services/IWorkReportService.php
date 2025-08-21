<?php

namespace App\Services;

interface IWorkReportService
{
    public function createReportFromFilamentResource(array $data);
    public function getAllExecutorsForThisWorkspace();
}
