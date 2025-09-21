<?php

namespace App\Filament\Resources\ContractAnnexes\Pages;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewContractAnnex extends ViewRecord
{
    protected static string $resource = ContractAnnexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon('heroicon-o-pencil')
                ->label('Edit Annex')
                ->color('edit'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "{$this->record->getFilamentName()}";
    }
}
