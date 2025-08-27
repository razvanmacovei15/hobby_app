<?php

namespace App\Filament\Resources\WorkspaceUserRoles\Pages;

use App\Filament\Resources\WorkspaceUserRoles\WorkspaceUserRoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkspaceUserRole extends EditRecord
{
    protected static string $resource = WorkspaceUserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
