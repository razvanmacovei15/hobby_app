<?php

namespace App\Filament\Resources;

use App\Enums\ExecutorType;
use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    // Disable Filament's automatic model->tenant ownership scoping for this resource
    protected static bool $isScopedToTenant = false;
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Workspace';
    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Executor';
    protected static ?string $pluralModelLabel = 'Workspace Executors';

    public static function shouldRegisterNavigation(): bool
    {
        // Only show this menu when a workspace tenant is selected
        return Filament::getTenant() !== null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot_executor_type')
                    ->label('Service Type')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if ($state === null || $state === '') {
                            return '—';
                        }
                        return ExecutorType::tryFrom($state)?->label() ?? ucfirst($state);
                    })
                    ->color(function ($state) {
                        return match (ExecutorType::tryFrom($state)) {
                            ExecutorType::ELECTRICAL => 'warning',
                            ExecutorType::MASONRY    => 'gray',
                            ExecutorType::PLUMBING   => 'info',
                            ExecutorType::FACADES    => 'success',
                            ExecutorType::FINISHES   => 'primary',
                            default                  => 'secondary',
                        };
                    }),

                Tables\Columns\TextColumn::make('j')
                    ->label('Reg. No.')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('cui')
                    ->label('CUI')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->toggleable(isToggledHiddenByDefault: true),

                // From the pivot join (see getEloquentQuery)
                Tables\Columns\IconColumn::make('pivot_is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pivot_created_at')
                    ->label('Added At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active')
                    ->placeholder('All')
                    ->queries(
                        true: fn(Builder $q) => $q->where('workspace_executors.is_active', true),
                        false: fn(Builder $q) => $q->where('workspace_executors.is_active', false),
                    ),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $tenant = Filament::getTenant();

        $query = parent::getEloquentQuery();

        if (!$tenant) {
            // No workspace selected -> show nothing
            return $query->whereRaw('1=0');
        }

        // Join the pivot so we can both filter and show pivot columns
        return $query
            ->select('companies.*')
            ->join('workspace_executors', 'companies.id', '=', 'workspace_executors.executor_id')
            ->where('workspace_executors.workspace_id', $tenant->getKey())
            ->addSelect([
                'workspace_executors.is_active as pivot_is_active',
                'workspace_executors.created_at as pivot_created_at',
                'workspace_executors.executor_type as pivot_executor_type',
            ]);
    }

    // Disable CRUD for now (purely read-only)
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
        ];
    }
}
