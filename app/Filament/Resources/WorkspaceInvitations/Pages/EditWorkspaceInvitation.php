<?php

namespace App\Filament\Resources\WorkspaceInvitations\Pages;

use App\Filament\Resources\WorkspaceInvitations\WorkspaceInvitationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkspaceInvitation extends EditRecord
{
    protected static string $resource = WorkspaceInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
