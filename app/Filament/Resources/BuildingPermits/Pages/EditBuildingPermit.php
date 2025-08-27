<?php

namespace App\Filament\Resources\BuildingPermits\Pages;

use App\Filament\Resources\BuildingPermits\BuildingPermitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuildingPermit extends EditRecord
{
    protected static string $resource = BuildingPermitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
