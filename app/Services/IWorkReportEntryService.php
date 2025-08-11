<?php

namespace App\Services;

use App\Enums\ServiceType;
use Illuminate\Support\Collection;

interface IWorkReportEntryService
{
    public function getServices(ServiceType $serviceType, int $companyId, ?int $contractId = null): Collection;
}
