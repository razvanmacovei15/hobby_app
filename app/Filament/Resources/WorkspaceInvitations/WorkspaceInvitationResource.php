<?php

namespace App\Filament\Resources\WorkspaceInvitations;

use App\Filament\Resources\WorkspaceInvitations\Pages\CreateWorkspaceInvitation;
use App\Filament\Resources\WorkspaceInvitations\Pages\EditWorkspaceInvitation;
use App\Filament\Resources\WorkspaceInvitations\Pages\ListWorkspaceInvitations;
use App\Filament\Resources\WorkspaceInvitations\Pages\ViewWorkspaceInvitation;
use App\Filament\Resources\WorkspaceInvitations\Schemas\WorkspaceInvitationForm;
use App\Filament\Resources\WorkspaceInvitations\Schemas\WorkspaceInvitationInfolist;
use App\Filament\Resources\WorkspaceInvitations\Tables\WorkspaceInvitationsTable;
use App\Models\WorkspaceInvitation;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkspaceInvitationResource extends Resource
{
    protected static ?string $model = WorkspaceInvitation::class;
    protected static string|null|BackedEnum $navigationIcon = Heroicon::OutlinedEnvelopeOpen;
    protected static ?string $navigationLabel = 'Workspace Invitations';
    protected static string|null|\UnitEnum $navigationGroup = 'Workspace';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return WorkspaceInvitationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkspaceInvitationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkspaceInvitationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkspaceInvitations::route('/'),
//            'create' => CreateWorkspaceInvitation::route('/create'),
//            'view' => ViewWorkspaceInvitation::route('/{record}'),
//            'edit' => EditWorkspaceInvitation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $workspace = Filament::getTenant();
        if (!$workspace) return null;

        $pendingCount = static::getModel()::where('workspace_id', $workspace->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->count();

        return $pendingCount > 0 ? (string) $pendingCount : null;
    }
}
