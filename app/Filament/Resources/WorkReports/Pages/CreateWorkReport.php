<?php

namespace App\Filament\Resources\WorkReports\Pages;

use App\Filament\Resources\WorkReports\WorkReportResource;
use App\Services\Implementations\WorkReportService;
use App\Services\IWorkReportService;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkReport extends CreateRecord
{
    protected static string $resource = WorkReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $workReportService = app(IWorkReportService::class);

        return $workReportService->createReportFromFilamentResource($data);
    }
}
