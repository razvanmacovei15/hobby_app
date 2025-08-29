<?php

namespace App\Filament\Resources\WorkspaceInvitationResource\Widgets;

use App\Models\WorkspaceInvitation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;

class InvitationStatWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $workspace = Filament::getTenant();

        if (!$workspace) {
            return [];
        }

        $totalInvitations = WorkspaceInvitation::where('workspace_id', $workspace->id)->count();
        $pendingInvitations = WorkspaceInvitation::where('workspace_id', $workspace->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->count();
        $acceptedInvitations = WorkspaceInvitation::where('workspace_id', $workspace->id)
            ->whereNotNull('accepted_at')
            ->count();
        $expiredInvitations = WorkspaceInvitation::where('workspace_id', $workspace->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '<', now())
            ->count();

        return [
            Stat::make('Total Invitations', $totalInvitations)
                ->description('All time invitations sent')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Pending', $pendingInvitations)
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Accepted', $acceptedInvitations)
                ->description('Successfully joined')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Expired', $expiredInvitations)
                ->description('Past deadline')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
