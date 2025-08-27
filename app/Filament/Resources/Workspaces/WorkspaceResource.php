<?php

namespace App\Filament\Resources\Workspaces;

use App\Filament\Resources\Workspaces\Pages\CreateWorkspace;
use App\Filament\Resources\Workspaces\Pages\EditWorkspace;
use App\Filament\Resources\Workspaces\Pages\ListWorkspaces;
use App\Filament\Resources\Workspaces\Schemas\WorkspaceForm;
use App\Filament\Resources\Workspaces\Tables\WorkspacesTable;
use App\Models\Workspace;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkspaceResource extends Resource
{
    protected static ?string $model = Workspace::class;

    protected static bool $isScopedToTenant = false;

    protected static string|null|\UnitEnum $navigationGroup = 'Company Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    public static function form(Schema $schema): Schema
    {
        return WorkspaceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkspacesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $workspace = Filament::getTenant();
        $ownerCompanyId = $workspace?->owner_id;

        if (!$ownerCompanyId) {
            // If no owner company, return empty query
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        // Show all workspaces owned by the same company
        return parent::getEloquentQuery()
            ->where('owner_id', $ownerCompanyId)
            ->with(['ownerCompany']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkspaces::route('/'),
            'create' => CreateWorkspace::route('/create'),
            'edit' => EditWorkspace::route('/{record}/edit'),
        ];
    }
}
