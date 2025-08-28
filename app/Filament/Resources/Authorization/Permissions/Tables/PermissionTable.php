<?php

namespace App\Filament\Resources\Authorization\Permissions\Tables;

use App\Enums\PermissionCategory;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class PermissionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Authorization')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->wrap(),

//                TextColumn::make('category')
//                    ->label('Category')
//                    ->formatStateUsing(fn($state) => $state?->label())
//                    ->badge()
//                    ->color('secondary')
//                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(PermissionCategory::options())
                    ->searchable()
                    ->preload(),
            ])
            ->groups([
                Group::make('category')
                    ->label('Category')
                    ->collapsible()
                    ->getTitleFromRecordUsing(function ($record) {
                        return $record->category?->label() ?? 'Other';
                    })
                    ->getDescriptionFromRecordUsing(function ($record) {
                        return $record->category?->description() ?? 'Other permissions';
                    }),
            ])
            ->defaultGroup('category')
            ->recordActions([
                // Read-only resource
            ])
            ->defaultSort('category');
    }
}
