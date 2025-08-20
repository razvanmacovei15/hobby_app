<?php

namespace App\Filament\Resources\ContractAnnexes;

use App\Filament\Resources\ContractAnnexes\Pages\CreateContractAnnex;
use App\Filament\Resources\ContractAnnexes\Pages\EditContractAnnex;
use App\Filament\Resources\ContractAnnexes\Pages\ListContractAnnexes;
use App\Filament\Resources\ContractAnnexes\Pages\ViewContractAnnex;
use App\Filament\Resources\ContractAnnexes\RelationManagers\ServicesRelationManager;
use App\Filament\Resources\ContractAnnexes\Schemas\ContractAnnexForm;
use App\Filament\Resources\ContractAnnexes\Schemas\ContractAnnexInfolist;
use App\Filament\Resources\ContractAnnexes\Tables\ContractAnnexesTable;
use App\Models\ContractAnnex;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContractAnnexResource extends Resource
{
    protected static ?string $model = ContractAnnex::class;
    protected static ?string $tenantOwnershipRelationshipName = null;
    protected static bool $shouldRegisterNavigation = false; // ✅ hide from sidebar
    protected static bool $isScopedToTenant = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static function form(Schema $schema): Schema
    {
        return ContractAnnexForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContractAnnexInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContractAnnexesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContractAnnexes::route('/'),
            'create' => CreateContractAnnex::route('/create'),
            'view' => ViewContractAnnex::route('/{record}'),
            'edit' => EditContractAnnex::route('/{record}/edit'),
        ];
    }
}
