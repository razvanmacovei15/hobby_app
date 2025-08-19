<?php

namespace App\Filament\Resources\ContractAnnexes\Pages;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContractAnnex extends CreateRecord
{
    protected static string $resource = ContractAnnexResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (request()->filled('contract_id')) {
            $data['contract_id'] = request()->integer('contract_id');
        }
        return $data;
    }
}
