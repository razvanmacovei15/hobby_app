<?php

namespace App\Filament\Resources\BuildingPermits\Pages;

use App\Filament\Resources\BuildingPermits\BuildingPermitResource;
use App\Services\IBuildingPermitService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuildingPermit extends EditRecord
{
    protected static string $resource = BuildingPermitResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load address relationship and flatten data for form
        if ($this->record->address) {
            $data['address'] = $this->record->address->attributesToArray();
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var IBuildingPermitService $service */
        $service = app(IBuildingPermitService::class);
        return $service->mutateFormDataBeforeUpdate($this->record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
