<?php

namespace App\Filament\Resources\CompanyEmployees;

use App\Filament\Resources\CompanyEmployees\Pages\CreateCompanyEmployee;
use App\Filament\Resources\CompanyEmployees\Pages\EditCompanyEmployee;
use App\Filament\Resources\CompanyEmployees\Pages\ListCompanyEmployees;
use App\Filament\Resources\CompanyEmployees\Schemas\CompanyEmployeeForm;
use App\Filament\Resources\CompanyEmployees\Tables\CompanyEmployeesTable;
use App\Models\CompanyEmployee;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyEmployeeResource extends Resource
{
    protected static ?string $model = CompanyEmployee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|null|\UnitEnum $navigationGroup = 'Company Management';

    protected static bool $isScopedToTenant = false;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return CompanyEmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyEmployeesTable::configure($table);
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

        return parent::getEloquentQuery()
            ->with(['user', 'company'])
            ->where('company_id', $workspace->owner_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyEmployees::route('/'),
            'create' => CreateCompanyEmployee::route('/create'),
            'edit' => EditCompanyEmployee::route('/{record}/edit'),
        ];
    }
}
