<?php

namespace App\Filament\Resources\WorkspaceInvitations\Pages;

use App\Filament\Pages\Invitations\InviteEmployee;
use App\Filament\Resources\WorkspaceInvitationResource\Widgets\InvitationStatWidget;
use App\Filament\Resources\WorkspaceInvitations\WorkspaceInvitationResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkspaceInvitations extends ListRecords
{
    protected static string $resource = WorkspaceInvitationResource::class;

    protected function getHeaderActions(): array
    {

        return [
            Action::make('invite_employee')
                ->label('Invite New Employee')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->url(InviteEmployee::getUrl()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InvitationStatWidget::class,
        ];
    }
}
