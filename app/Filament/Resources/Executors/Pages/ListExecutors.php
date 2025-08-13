<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;

class ListExecutors extends ListRecords
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
            CreateAction::make()
                ->label('Register executor') // 👈 your custom label
                ->icon('heroicon-m-plus-circle'),
        ];
    }
}
