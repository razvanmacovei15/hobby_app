<?php

namespace App\Filament\Resources\Permission\Permissions;

use App\Filament\Resources\Permission\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permission\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permission\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permission\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permission\Permissions\Tables\PermissionsTable;
use App\Models\Permission\Permission;
use App\Models\Workspace;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static string|UnitEnum|null $navigationGroup = 'Workspace Management';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?string $modelLabel = 'Permission';

    protected static ?string $pluralModelLabel = 'Permissions';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
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
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
