<?php

namespace App\Filament\Resources\Permission\Roles\Schemas;

use App\Models\Permission\Permission;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Use lowercase with dashes (e.g., project-manager)'),

                TextInput::make('display_name')
                    ->label('Display Name')
                    ->maxLength(255)
                    ->helperText('Human readable name (e.g., Project Manager)'),

                Section::make('Permissions')
                    ->description('Select the permissions for this role')
                    ->schema([
                        CheckboxList::make('permissions')
                            ->label('')
                            ->relationship('permissions', 'name')
                            ->options(function () {
                                $tenant = Filament::getTenant();

                                return Permission::query()
                                    ->where('workspace_id', $tenant->id)
                                    ->get()
                                    ->mapWithKeys(function ($permission) {
                                        return [
                                            $permission->id => ucwords(str_replace(['-', '.'], ' ', $permission->name))
                                        ];
                                    });
                            })
                            ->descriptions(function () {
                                $tenant = Filament::getTenant();

                                return Permission::query()
                                    ->where('workspace_id', $tenant->id)
                                    ->get()
                                    ->mapWithKeys(function ($permission) {
                                        return [
                                            $permission->id => $permission->description ?? ''
                                        ];
                                    });
                            })
                            ->columns(2)
                            ->gridDirection('row'),
                    ])
                    ->collapsible(),
            ]);
    }
}
