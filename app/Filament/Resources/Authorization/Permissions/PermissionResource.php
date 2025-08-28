<?php

namespace App\Filament\Resources\Authorization\Permissions;

use App\Filament\Resources\Authorization\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Authorization\Permissions\Pages\EditPermission;
use App\Filament\Resources\Authorization\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Authorization\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Authorization\Permissions\Tables\PermissionTable;
use App\Models\Permission\Permission;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?string $modelLabel = 'Permission';

    protected static ?string $pluralModelLabel = 'Permissions';

    protected static string|null|\UnitEnum $navigationGroup = 'Authorization';

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionTable::configure($table);
    }

    public function readOnly(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        $currentWorkspace = Filament::getTenant();

        if ($currentWorkspace) {
            return parent::getEloquentQuery()
                ->where('workspace_id', $currentWorkspace->id);
        }

        return parent::getEloquentQuery();
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
