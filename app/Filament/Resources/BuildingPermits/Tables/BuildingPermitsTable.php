<?php

namespace App\Filament\Resources\BuildingPermits\Tables;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BuildingPermitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('permit_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('permit_type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (PermitStatus $state) => match ($state) {
                        PermitStatus::PENDING => 'warning',
                        PermitStatus::APPROVED => 'success',
                        PermitStatus::REJECTED => 'danger',
                        PermitStatus::EXPIRED => 'gray',
                        PermitStatus::REVOKED => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('workspace.name')
                    ->sortable(),

                TextColumn::make('buildings_count')
                    ->counts('buildings')
                    ->label('Buildings'),

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
                SelectFilter::make('permit_type')
                    ->options(PermitType::class),

                SelectFilter::make('status')
                    ->options(PermitStatus::class),

                SelectFilter::make('workspace_id')
                    ->relationship('workspace', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
