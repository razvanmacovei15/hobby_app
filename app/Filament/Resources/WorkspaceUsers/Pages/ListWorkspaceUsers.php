<?php

namespace App\Filament\Resources\WorkspaceUsers\Pages;

use App\Filament\Resources\WorkspaceUsers\WorkspaceUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkspaceUsers extends ListRecords
{
    protected static string $resource = WorkspaceUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
