<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use App\Services\IExecutorService;
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
}
