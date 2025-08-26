<?php

namespace App\Filament\Resources\ContractAnnexes\Pages;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use App\Filament\Resources\Contracts\ContractResource;
use Filament\Actions\Action;
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

    protected function getRedirectUrl(): string
    {
        // after the annex is created, go to the parent Contract view
        return ContractResource::getUrl('view', [
            'record' => $this->record->contract_id,
        ]);
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Save')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->extraAttributes([
                'style' => 'color: black;',
            ]); // or any other color like 'primary', 'warning', etc.
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Save & Create Another')
            ->icon('heroicon-o-document-duplicate')
            ->extraAttributes([
                'style' => 'color: black;',
            ]); // or any other color like 'primary', 'warning', etc.
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Cancel')
            ->icon('heroicon-o-x-circle')
            ->color('cancel')
            ->extraAttributes([
                'style' => 'color: black;',
            ]); // or any other color like 'primary', 'warning', etc.
    }
}
