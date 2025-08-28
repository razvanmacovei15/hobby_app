<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use App\Models\CompanyEmployee;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Permission\Role;
use Filament\Facades\Filament;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $currentWorkspace = Filament::getTenant();

        return $schema
            ->components([
                Section::make('Add User to Workspace')
                    ->description('Select a company employee and assign roles to add them to this workspace.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Select Employee')
                            ->placeholder('Choose an employee from the company')
                            ->options(function () use ($currentWorkspace) {
                                if (!$currentWorkspace) {
                                    return [];
                                }

                                // Get users who are company employees but not in this workspace
                                return CompanyEmployee::where('company_id', $currentWorkspace->owner_id)
                                    ->with('user')
                                    ->whereHas('user', function ($query) use ($currentWorkspace) {
                                        $query->whereDoesntHave('workspaces', function ($subQuery) use ($currentWorkspace) {
                                            $subQuery->where('workspace_id', $currentWorkspace->id);
                                        });
                                    })
                                    ->get()
                                    ->pluck('user.first_name', 'user.id')
                                    ->map(function ($firstName, $userId) {
                                        $user = User::find($userId);
                                        return $user ? $user->getFilamentName() : $firstName;
                                    });
                            })
                            ->searchable()
                            ->required()
                            ->helperText('Only employees not already in this workspace are shown')
                            ->hiddenOn('edit'),

                        Select::make('roles')
                            ->label('Assign Roles')
                            ->placeholder('Select roles for this user')
                            ->options(function () use ($currentWorkspace) {
                                if (!$currentWorkspace) {
                                    return [];
                                }

                                return Role::where('workspace_id', $currentWorkspace->id)
                                    ->get()
                                    ->pluck('display_name', 'id')
                                    ->map(fn ($displayName, $id) => $displayName ?: Role::find($id)?->name);
                            })
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Select one or more roles for this user in the workspace')
                            ->required(),
                    ])
                    ->columns(1)
            ]);
    }
}
