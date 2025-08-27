<?php

namespace App\Filament\Resources\WorkspaceUserRoles\Tables;

use App\Models\Permission\Role;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Facades\Filament;

class WorkspaceUserRolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('role.name')
                    ->label('Role')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('-', ' ', $state)))
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'workspace-admin' => 'danger',
                        'project-manager' => 'warning',
                        'site-supervisor' => 'info', 
                        'financial-manager' => 'success',
                        'worker' => 'gray',
                        'viewer' => 'secondary',
                        default => 'primary'
                    })
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->label('Role')
                    ->options(function () {
                        $tenant = Filament::getTenant();
                        
                        return Role::query()
                            ->where('workspace_id', $tenant->id)
                            ->get()
                            ->mapWithKeys(function ($role) {
                                return [$role->id => ucwords(str_replace('-', ' ', $role->name))];
                            });
                    })
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteBulkAction::make()
                    ->label('Remove Role'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Remove Selected Roles'),
                ]),
            ]);
    }
}
