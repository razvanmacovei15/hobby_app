<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use App\Services\IExecutorService;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class CreateExecutor extends CreateRecord
{
    protected static string $resource = ExecutorResource::class;

    public function getTitle(): string
    {
        return 'Register executor';
    }

    /** In your Edit/Create page class */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var IExecutorService $svc */
        $svc = app(IExecutorService::class);
        return $svc->mutateFormDataBeforeSave($data, null);
    }

    public function form(Schema $schema): Schema
    {
        return ExecutorForm::configure($schema)->state([
            'executor' => [
                'address' => [],
                'representative' => [],
            ],
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
