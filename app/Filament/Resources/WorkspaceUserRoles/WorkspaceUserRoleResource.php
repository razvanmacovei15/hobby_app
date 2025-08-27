<?php

namespace App\Filament\Resources\WorkspaceUserRoles;

use App\Filament\Resources\WorkspaceUserRoles\Pages\CreateWorkspaceUserRole;
use App\Filament\Resources\WorkspaceUserRoles\Pages\EditWorkspaceUserRole;
use App\Filament\Resources\WorkspaceUserRoles\Pages\ListWorkspaceUserRoles;
use App\Filament\Resources\WorkspaceUserRoles\Schemas\WorkspaceUserRoleForm;
use App\Filament\Resources\WorkspaceUserRoles\Tables\WorkspaceUserRolesTable;
use App\Models\WorkspaceUserRole;
use App\Models\Workspace;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class WorkspaceUserRoleResource extends Resource
{
    protected static ?string $model = WorkspaceUserRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Workspace Management';

    protected static ?string $navigationLabel = 'User Roles';
    protected static bool $isScopedToTenant = false;

    protected static ?string $modelLabel = 'User Role';

    protected static ?string $pluralModelLabel = 'User Roles';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return WorkspaceUserRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkspaceUserRolesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $tenant = Filament::getTenant();

        if (!$tenant instanceof Workspace) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->whereHas('role', function ($roleQuery) use ($tenant) {
                $roleQuery->where('workspace_id', $tenant->id);
            })
            ->where('model_type', \App\Models\User::class);
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
            'index' => ListWorkspaceUserRoles::route('/'),
            'create' => CreateWorkspaceUserRole::route('/create'),
            'edit' => EditWorkspaceUserRole::route('/{record}/edit'),
        ];
    }
}
