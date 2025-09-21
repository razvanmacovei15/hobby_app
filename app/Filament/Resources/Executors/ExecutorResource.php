<?php

namespace App\Filament\Resources\Executors;

use App\Filament\Resources\Executors\Pages\CreateExecutor;
use App\Filament\Resources\Executors\Pages\EditExecutor;
use App\Filament\Resources\Executors\Pages\ListExecutors;
use App\Filament\Resources\Executors\Pages\ViewExecutor;
use App\Filament\Resources\Executors\Schemas\ExecutorForm;
use App\Filament\Resources\Executors\Schemas\ExecutorInfolist;
use App\Filament\Resources\Executors\Tables\ExecutorsTable;
use App\Models\WorkspaceExecutor;
use App\Services\IExecutorService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExecutorResource extends Resource
{
    protected static ?string $model = WorkspaceExecutor::class;

    // 👇 new
    protected static string|null|\UnitEnum $navigationGroup = 'Execution Network';

    // swap the basic icon for something with personality
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $recordTitleAttribute = 'filament_name';

    public static function form(Schema $schema): Schema
    {
        return ExecutorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExecutorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExecutorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExecutors::route('/'),
            'create' => CreateExecutor::route('/create'),
            'view' => ViewExecutor::route('/{record}'),
            'edit' => EditExecutor::route('/{record}/edit'),
        ];
    }

    /** Delegate the index query to the service (tenant-scoped). */
    public static function getEloquentQuery(): Builder
    {
        /** @var IExecutorService $svc */
        $svc = app(IExecutorService::class);

        // You can pass true/false to onlyActive if you want to force a filter here.
        return $svc->queryForCurrentWorkspace();
    }
}
