<?php

namespace App\Filament\Resources\WorkspaceUserRoles\Schemas;

use App\Models\Permission\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class WorkspaceUserRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('model_id')
                    ->label('User')
                    ->options(function () {
                        $tenant = Filament::getTenant();
                        
                        return User::query()
                            ->whereHas('workspaces', function ($query) use ($tenant) {
                                $query->where('workspace_id', $tenant->id);
                            })
                            ->get()
                            ->mapWithKeys(function ($user) {
                                return [$user->id => $user->getFilamentName()];
                            });
                    })
                    ->searchable()
                    ->required()
                    ->preload(),

                Select::make('role_id')
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
                    ->searchable()
                    ->required()
                    ->preload(),
            ]);
    }
}
