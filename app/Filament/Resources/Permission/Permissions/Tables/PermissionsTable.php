<?php

namespace App\Filament\Resources\Permission\Permissions\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Permission')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace(['-', '.'], ' ', $state)))
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        str_contains($state, 'manage') => 'danger',
                        str_contains($state, 'create') => 'success',
                        str_contains($state, 'edit') => 'warning',
                        str_contains($state, 'delete') => 'danger',
                        str_contains($state, 'approve') => 'info',
                        str_contains($state, 'view') => 'gray',
                        default => 'primary'
                    })
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->wrap(),
                
                TextColumn::make('category')
                    ->label('Category')
                    ->formatStateUsing(function ($record) {
                        $name = $record->name;
                        if (str_contains($name, 'workspace')) return 'Workspace';
                        if (str_contains($name, 'users')) return 'Users';
                        if (str_contains($name, 'contracts')) return 'Contracts';
                        if (str_contains($name, 'work-reports')) return 'Work Reports';
                        if (str_contains($name, 'building-permits')) return 'Building Permits';
                        if (str_contains($name, 'financial')) return 'Financial';
                        if (str_contains($name, 'sites')) return 'Sites';
                        return 'Other';
                    })
                    ->badge()
                    ->color('secondary'),
                
                TextColumn::make('roles_count')
                    ->label('Used by Roles')
                    ->counts('roles')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'workspace' => 'Workspace',
                        'users' => 'Users', 
                        'contracts' => 'Contracts',
                        'work-reports' => 'Work Reports',
                        'building-permits' => 'Building Permits',
                        'financial' => 'Financial',
                        'sites' => 'Sites',
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['value']) {
                            return $query;
                        }
                        
                        return $query->where('name', 'like', '%' . $data['value'] . '%');
                    }),
            ])
            ->recordActions([
                // No actions - read only
            ])
            ->bulkActions([
                // No bulk actions - read only  
            ])
            ->defaultSort('name');
    }
}
