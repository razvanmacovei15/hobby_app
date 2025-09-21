<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewContract extends ViewRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon('heroicon-o-pencil')
                ->label('Edit Contract')
                ->color('edit')
//                ->extraAttributes(['style' => 'color: white']),
        ];
    }

    public function getTitle(): string
    {
        return "{$this->record->getFilamentName()}";
    }
}
