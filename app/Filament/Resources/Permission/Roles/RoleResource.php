<?php

namespace App\Filament\Resources\Permission\Roles;

use App\Filament\Resources\Permission\Roles\Pages\CreateRole;
use App\Filament\Resources\Permission\Roles\Pages\EditRole;
use App\Filament\Resources\Permission\Roles\Pages\ListRoles;
use App\Filament\Resources\Permission\Roles\Schemas\RoleForm;
use App\Filament\Resources\Permission\Roles\Tables\RolesTable;
use App\Models\Permission\Role;
use App\Models\Workspace;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Workspace Management';

    protected static ?string $navigationLabel = 'Roles';

    protected static ?string $modelLabel = 'Role';

    protected static ?string $pluralModelLabel = 'Roles';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $tenant = Filament::getTenant();

        if (!$tenant instanceof Workspace) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('workspace_id', $tenant->id);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
