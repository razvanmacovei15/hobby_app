<?php

namespace App\Filament\Resources\ContractAnnexes\Pages;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContractAnnexes extends ListRecords
{
    protected static string $resource = ContractAnnexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
