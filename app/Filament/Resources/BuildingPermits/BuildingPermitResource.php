<?php

namespace App\Filament\Resources\BuildingPermits;

use App\Filament\Resources\BuildingPermits\Pages\CreateBuildingPermit;
use App\Filament\Resources\BuildingPermits\Pages\EditBuildingPermit;
use App\Filament\Resources\BuildingPermits\Pages\ListBuildingPermits;
use App\Filament\Resources\BuildingPermits\Pages\ViewBuildingPermit;
use App\Filament\Resources\BuildingPermits\Schemas\BuildingPermitForm;
use App\Filament\Resources\BuildingPermits\Schemas\BuildingPermitInfolist;
use App\Filament\Resources\BuildingPermits\Tables\BuildingPermitsTable;
use App\Models\BuildingPermit;
use App\Services\IBuildingPermitService;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BuildingPermitResource extends Resource
{
    protected static ?string $model = BuildingPermit::class;

    protected static bool $isScopedToTenant = false;

    protected static string|null|\UnitEnum $navigationGroup = 'Company Management';

    protected static ?string $recordTitleAttribute = 'display_name';

    public static function getModelLabel(): string
    {
        return 'Building Permit';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Building Permits';
    }

    public static function getNavigationLabel(): string
    {
        return 'Building Permits';
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->permit_number . '/' . $record->issuance_year . ' - ' . $record->name;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function form(Schema $schema): Schema
    {
        return BuildingPermitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BuildingPermitsTable::configure($table);
    }

    public static function infolist(Schema $infolist): Schema
    {
        return BuildingPermitInfolist::configure($infolist);
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

        // Use the service to get permits for this company
        $service = app(IBuildingPermitService::class);
        $permits = $service->getAllPermitsForCompanyId($ownerCompanyId);

        // Return query filtered by the permit IDs from service
        $permitIds = $permits->pluck('id')->toArray();

        return parent::getEloquentQuery()->whereIn('id', $permitIds);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBuildingPermits::route('/'),
            'create' => CreateBuildingPermit::route('/create'),
//            'view' => ViewBuildingPermit::route('/{record}'),
            'edit' => EditBuildingPermit::route('/{record}/edit'),
        ];
    }
}
