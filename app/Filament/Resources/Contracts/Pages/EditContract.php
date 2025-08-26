<?php

namespace App\Filament\Resources\Contracts\Pages;

use App\Filament\Resources\Contracts\ContractResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Save')
            ->icon('heroicon-o-check-circle')
            ->color('success')
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

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->icon('heroicon-o-eye'),
            DeleteAction::make()
                ->color('delete')
                ->icon('heroicon-o-trash'),
        ];
    }
}
