<?php

namespace App\Filament\Resources\WorkspaceUsers\Pages;

use App\Filament\Resources\WorkspaceUsers\WorkspaceUserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkspaceUser extends EditRecord
{
    protected static string $resource = WorkspaceUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
