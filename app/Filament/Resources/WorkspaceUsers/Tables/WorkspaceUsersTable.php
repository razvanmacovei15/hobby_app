<?php

namespace App\Filament\Resources\WorkspaceUsers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class WorkspaceUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.id')
                    ->label('Full Name')
                    ->formatStateUsing(fn($record) => $record->user->getFilamentName())
                    ->searchable(['user.first_name', 'user.last_name'])
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(['user.email'])
                    ->sortable(),

                TextColumn::make('roles')
                    ->label('Roles')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $currentWorkspace = Filament::getTenant();
                        $roles = $record->user->getWorkspaceRoles($currentWorkspace);
                        return $roles->pluck('display_name')->toArray();
                    })
                    ->listWithLineBreaks(),

                TextColumn::make('workspace.name')
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
                IconColumn::make('is_default')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
//                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
