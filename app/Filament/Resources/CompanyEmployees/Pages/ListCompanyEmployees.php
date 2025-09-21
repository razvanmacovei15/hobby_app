<?php

namespace App\Filament\Resources\CompanyEmployees\Pages;

use App\Filament\Resources\CompanyEmployees\CompanyEmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanyEmployees extends ListRecords
{
    protected static string $resource = CompanyEmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus')->label('Add Employee'),
        ];
    }
}
