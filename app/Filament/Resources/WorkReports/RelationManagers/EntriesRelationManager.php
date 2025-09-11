<?php

namespace App\Filament\Resources\WorkReports\RelationManagers;

use App\Enums\WorkReportStatus;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    private function isWorkReportApproved(): bool
    {
        return $this->ownerRecord && $this->ownerRecord->status === WorkReportStatus::APPROVED;
    }

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        if (!is_subclass_of($pageClass, ViewRecord::class)) {
            return false;
        }

        return auth()->user()->can('contracted-services.view');
    }

    public function isReadOnly(): bool
    {
        return !auth()->user()->can('contracted-services.edit') || $this->isWorkReportApproved();
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->with('service'); // this $query is guaranteed to be a Builder
            })
            ->recordTitleAttribute('order')
            ->defaultSort('order')
            ->columns([
                // If all entries point to ContractedService, this “just works”:
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.unit_of_measure')
                    ->label('Unit'),

                TextColumn::make('service.price_per_unit_of_measure')
                    ->label('Price / Unit')
                    ->numeric(2),
                TextColumn::make('service_type')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([

                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('delete')
                    ->visible(fn() => auth()->user()->can('contracted-services.delete') && !$this->isWorkReportApproved()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->can('contracted-services.delete') && !$this->isWorkReportApproved()),
                ]),
            ]);
    }
}
