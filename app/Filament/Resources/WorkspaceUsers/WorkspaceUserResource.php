<?php

namespace App\Filament\Resources\WorkspaceUsers;

use App\Filament\Resources\WorkspaceUsers\Pages\CreateWorkspaceUser;
use App\Filament\Resources\WorkspaceUsers\Pages\EditWorkspaceUser;
use App\Filament\Resources\WorkspaceUsers\Pages\ListWorkspaceUsers;
use App\Filament\Resources\WorkspaceUsers\Schemas\WorkspaceUserForm;
use App\Filament\Resources\WorkspaceUsers\Tables\WorkspaceUsersTable;
use App\Models\WorkspaceUser;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkspaceUserResource extends Resource
{
    protected static ?string $model = WorkspaceUser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static string|null|\UnitEnum $navigationGroup = 'Workspace';

    public static function form(Schema $schema): Schema
    {
        return WorkspaceUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkspaceUsersTable::configure($table);
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
            'index' => ListWorkspaceUsers::route('/'),
            'create' => CreateWorkspaceUser::route('/create'),
            'edit' => EditWorkspaceUser::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $workspace = Filament::getTenant();
        return WorkspaceUser::query()
            ->where('workspace_id', $workspace->id);
    }
}
