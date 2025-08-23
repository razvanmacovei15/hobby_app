<?php

namespace App\Filament\Resources\WorkReports;

use App\Filament\Resources\WorkReports\Pages\CreateWorkReport;
use App\Filament\Resources\WorkReports\Pages\EditWorkReport;
use App\Filament\Resources\WorkReports\Pages\ListWorkReports;
use App\Filament\Resources\WorkReports\Pages\ViewWorkReport;
use App\Filament\Resources\WorkReports\RelationManagers\EntriesRelationManager;
use App\Filament\Resources\WorkReports\Schemas\WorkReportForm;
use App\Filament\Resources\WorkReports\Schemas\WorkReportInfolist;
use App\Filament\Resources\WorkReports\Tables\WorkReportsTable;
use App\Models\WorkReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkReportResource extends Resource
{
    protected static bool $isScopedToTenant = false;
    protected static ?string $tenantOwnershipRelationshipName = null;
    protected static ?string $model = WorkReport::class;
    protected static string|null|\UnitEnum $navigationGroup = 'Execution Network';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'report_number';

    public static function form(Schema $schema): Schema
    {
        return WorkReportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkReports::route('/'),
            'create' => CreateWorkReport::route('/create'),
            'view' => ViewWorkReport::route('/{record}'),
            'edit' => EditWorkReport::route('/{record}/edit'),
        ];
    }
}
