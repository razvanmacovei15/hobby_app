<?php

namespace App\Filament\Resources\WorkspaceUsers\Schemas;

use App\Models\Permission\Role;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkspaceUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->getFilamentName() . ' (' . $record->email . ')')
                    ->searchable(['first_name', 'last_name', 'email'])
                    ->required()
                    ->disabledOn('edit'),

//                Select::make('workspace_id')
//                    ->relationship('workspace', 'name')
//                    ->default(fn() => Filament::getTenant()?->id)
//                    ->disabled()
//                    ->dehydrated()
//                    ->required(),

//                Toggle::make('is_default')
//                    ->label('Set as Default Workspace')
//                    ->helperText('Make this the user\'s default workspace')
//                    ->default(false),

                Section::make('Role Assignment')
                    ->description('Assign roles to this user in the workspace')
                    ->schema([
                        CheckboxList::make('role_ids')
                            ->label('Roles')
                            ->options(function () {
                                $workspace = Filament::getTenant();
                                if (!$workspace) {
                                    return [];
                                }

                                return Role::query()
                                    ->where('workspace_id', $workspace->id)
                                    ->pluck('display_name', 'id')
                                    ->toArray();
                            })
                            ->descriptions(function () {
                                $workspace = Filament::getTenant();
                                if (!$workspace) {
                                    return [];
                                }

                                return Role::query()
                                    ->where('workspace_id', $workspace->id)
                                    ->get()
                                    ->mapWithKeys(function ($role) {
                                        $permissionCount = $role->permissions()->count();
                                        $description = $permissionCount > 0
                                            ? "Has access to {$permissionCount} permissions"
                                            : "No permissions assigned";
                                        return [$role->id => $description];
                                    })
                                    ->toArray();
                            })
                            ->afterStateHydrated(function (CheckboxList $component, $state, $record) {
                                if ($record && $record->user) {
                                    $workspace = Filament::getTenant();
                                    if ($workspace) {
                                        $roleIds = $record->user->getWorkspaceRoles($workspace)
                                            ->pluck('id')
                                            ->toArray();
                                        $component->state($roleIds);
                                    }
                                }
                            })
                            ->dehydrated(true)
                            ->columns(2)
                            ->gridDirection('row')
                            ->bulkToggleable(),
                    ])
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }
}
