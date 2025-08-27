<?php

namespace App\Filament\Resources\WorkspaceUserRoles\Pages;

use App\Filament\Resources\WorkspaceUserRoles\WorkspaceUserRoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkspaceUserRoles extends ListRecords
{
    protected static string $resource = WorkspaceUserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
