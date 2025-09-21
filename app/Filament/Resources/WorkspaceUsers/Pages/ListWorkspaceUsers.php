<?php

namespace App\Filament\Resources\WorkspaceUsers\Pages;

use App\Filament\Pages\Invitations\InviteEmployee;
use App\Filament\Resources\WorkspaceUsers\WorkspaceUserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListWorkspaceUsers extends ListRecords
{
    protected static string $resource = WorkspaceUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Invite Employee to Workspace')
                ->color('success')
                ->icon('heroicon-o-user-plus')
                ->label('Invite Employee to Workspace')
                ->url(InviteEmployee::getUrl()),
        ];
    }
}
