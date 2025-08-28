<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Facades\Filament;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('workspace_roles')
                    ->label('Roles')
                    ->badge()
                    ->separator(',')
                    ->getStateUsing(function ($record) {
                        $currentWorkspace = Filament::getTenant();
                        if (!$currentWorkspace) {
                            return [];
                        }

                        $workspaceRoles = $record->roles()
                            ->where('workspace_id', $currentWorkspace->id)
                            ->get();

                        return $workspaceRoles
                            ->map(fn ($role) => $role->display_name ?? $role->name)
                            ->toArray();
                    }),
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
//                ViewAction::make(),
//                EditAction::make(),
                DeleteAction::make()
                    ->visible(function ($record) {
                        $currentUser = auth()->user();
                        $currentWorkspace = Filament::getTenant();

                        // Don't show delete for current logged-in user
                        if ($currentUser->id === $record->id) {
                            return false;
                        }

                        // Don't show delete for workspace admins
                        if ($currentWorkspace && $record->hasWorkspaceRole($currentWorkspace, 'workspace-admin')) {
                            return false;
                        }

                        return true;
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
