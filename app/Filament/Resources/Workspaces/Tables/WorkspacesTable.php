<?php

namespace App\Filament\Resources\Workspaces\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkspacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Workspace Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ownerCompany.name')
                    ->label('Owner Company')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('buildingPermit.permit_number')
                    ->label('Building Permit')
                    ->placeholder('No permit')
                    ->sortable(),

                TextColumn::make('buildingPermit.status')
                    ->label('Permit Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        \App\Enums\PermitStatus::PENDING => 'warning',
                        \App\Enums\PermitStatus::APPROVED => 'success',
                        \App\Enums\PermitStatus::REJECTED => 'danger',
                        \App\Enums\PermitStatus::EXPIRED => 'gray',
                        \App\Enums\PermitStatus::REVOKED => 'danger',
                        default => 'gray',
                    })
                    ->placeholder('No permit'),

                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users'),

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
