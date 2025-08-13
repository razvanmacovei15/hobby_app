<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use App\Models\WorkspaceExecutor;
use App\Services\IExecutorService;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewExecutor extends ViewRecord
{
    protected static string $resource = ExecutorResource::class;
    public function form(Schema $schema): Schema
    {
        return ExecutorForm::configure($schema)
            ->record($this->record); // 👈 v4: bind the model for editing
    }
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
