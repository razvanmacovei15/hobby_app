<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContracts extends ListRecords
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getEmptyStateHeading(): ?string
    {
        return 'No contracts found for this workspace';
    }

    protected function getEmptyStateDescription(): ?string
    {
        return 'Contracts are shown read-only for now. You’ll attach executors when editing a contract later.';
    }
}
