<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExecutors extends ListRecords
{
    protected static string $resource = ExecutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
