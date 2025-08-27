<?php

namespace App\Filament\Resources\BuildingPermits\Pages;

use App\Filament\Resources\BuildingPermits\BuildingPermitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuildingPermits extends ListRecords
{
    protected static string $resource = BuildingPermitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
