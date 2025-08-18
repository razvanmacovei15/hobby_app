<?php

namespace App\Filament\Resources\ContractAnnexes\Pages;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditContractAnnex extends EditRecord
{
    protected static string $resource = ContractAnnexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
