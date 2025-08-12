<?php

namespace App\Services;

use App\Enums\ServiceType;
use Illuminate\Support\Collection;

interface IWorkReportService
{
    public function getServices();
    public function getExtraServices();
}
