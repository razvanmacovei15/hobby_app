<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use App\Models\Permission\Role;
use Filament\Facades\Filament;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $currentWorkspace = Filament::getTenant();
        $currentUser = auth()->user();

        // Check if user has admin role in current workspace
        $isWorkspaceAdmin = false;
        if ($currentUser && $currentWorkspace) {
            $adminRoles = $currentUser->roles()
                ->where('workspace_id', $currentWorkspace->id)
                ->whereIn('name', ['super-admin', 'admin'])
                ->exists();
            $isWorkspaceAdmin = $adminRoles;
        }

        $tabs = [
            Tab::make('Basic Information')
                ->schema([
                    TextInput::make('first_name')
                        ->disabled(fn ($record) => $record && auth()->id() !== $record->id),

                    TextInput::make('last_name')
                        ->disabled(fn ($record) => $record && auth()->id() !== $record->id),

                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->disabled(fn ($record) => $record && auth()->id() !== $record->id),
                ]),
        ];

        if ($isWorkspaceAdmin) {
            $tabs[] = Tab::make('Role')
                ->schema([
                    Select::make('roles')
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('workspace_id', $currentWorkspace->id)
                        )
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name ?? $record->name)
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->placeholder('Select roles for this user')
                        ->helperText('Users can have multiple roles within the current workspace')
                        ->disabled(fn ($record) => $record && $record->hasWorkspaceRole($currentWorkspace, 'workspace-admin') && auth()->id() !== $record->id),
                ]);
        }

        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs($tabs)
                    ->columnSpanFull()
            ]);
    }
}
