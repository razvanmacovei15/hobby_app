<?php

namespace App\Filament\Resources\Executors\Pages;

use App\Filament\Resources\Executors\ExecutorResource;
use App\Models\WorkspaceExecutor;
use App\Services\IExecutorService;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExecutor extends ViewRecord
{
    protected static string $resource = ExecutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
