<?php

namespace App\Filament\Resources\WorkspaceInvitations\Pages;

use App\Filament\Resources\WorkspaceInvitations\WorkspaceInvitationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkspaceInvitation extends ViewRecord
{
    protected static string $resource = WorkspaceInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
