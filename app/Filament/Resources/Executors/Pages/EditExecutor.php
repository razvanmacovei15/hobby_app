<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use App\Services\IExecutorService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Filament\Schemas\Schema;

class EditExecutor extends EditRecord
{
    protected static string $resource = ExecutorResource::class;

    public function getTitle(): string
    {
        return "Edit - {$this->record->getFilamentName()}";
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var IExecutorService $svc */
        $svc = app(IExecutorService::class);

        // Pass the current related company if present
        $currentExecutor = $this->record->executor ?? null;

        return $svc->mutateFormDataBeforeSave($data, $currentExecutor);
    }

    public function form(Schema $schema): Schema
    {
        return ExecutorForm::configure($schema)
            ->record($this->record); // 👈 v4: bind the model for editing
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->icon('heroicon-o-eye'),
            DeleteAction::make()->icon('heroicon-o-trash')->color('delete'),
        ];
    }

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
}
