<?php

namespace App\Filament\Resources\BuildingPermits\Pages;

use App\Filament\Resources\BuildingPermits\BuildingPermitResource;
use App\Services\IBuildingPermitService;
use Filament\Resources\Pages\CreateRecord;

class CreateBuildingPermit extends CreateRecord
{
    protected static string $resource = BuildingPermitResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var IBuildingPermitService $service */
        $service = app(IBuildingPermitService::class);
        return $service->mutateFormDataBeforeCreate($data);
    }
}
