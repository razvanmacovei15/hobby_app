<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Models\Contract;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Workspace';
    protected static ?int $navigationSort = 20;
    protected static ?string $pluralModelLabel = 'Contracts';

    protected static bool $isScopedToTenant = false;

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::getTenant() !== null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Executor company
                Tables\Columns\TextColumn::make('executor.name')
                    ->label('Executor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contract_number')
                    ->label('Contract No.')
                    ->searchable()
                    ->sortable(),

                // Optional: show executor_type from the workspace_executors pivot
                Tables\Columns\TextColumn::make('pivot_executor_type')
                    ->label('Services Type')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if ($state === null || $state === '') return '—';
                        // If your enum is int-backed, cast to (int) first
                        return \App\Enums\ExecutorType::tryFrom($state)?->label() ?? ucfirst((string)$state);
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                // Beneficiary (for clarity; will usually be the workspace owner)
                Tables\Columns\TextColumn::make('beneficiary.name')
                    ->label('Beneficiary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('sign_date')
                    ->date()
                    ->label('Signed')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->label('Start')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->label('End')
                    ->sortable(),
            ])
            ->filters([
                // Add simple filters when you want; keeping it minimal for now
            ])
            ->actions([])      // read-only
            ->bulkActions([]); // read-only
    }

    public static function getEloquentQuery(): Builder
    {
        $tenant = Filament::getTenant(); // current Workspace
        $query  = parent::getEloquentQuery()->with(['beneficiary', 'executor']);

        if (! $tenant) {
            return $query->whereRaw('1=0');
        }

        // Show only contracts where the beneficiary is the workspace owner,
        // and the executor is one of the workspace’s executors.
        // Also pull the executor_type from the pivot (if you want it in the table).
        return $query
            ->select('contracts.*')
            ->join('companies as exec', 'contracts.executor_id', '=', 'exec.id')
            ->join('companies as ben', 'contracts.beneficiary_id', '=', 'ben.id')
            ->leftJoin('workspace_executors as we', function ($join) use ($tenant) {
                $join->on('we.executor_id', '=', 'contracts.executor_id')
                    ->where('we.workspace_id', '=', $tenant->getKey());
            })
            ->where('contracts.beneficiary_id', '=', $tenant->owner_id)
            ->addSelect([
                'we.executor_type as pivot_executor_type',
            ]);
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => ContractResource\Pages\ListContracts::route('/'),
        ];
    }
}
