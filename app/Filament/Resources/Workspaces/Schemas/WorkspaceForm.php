<?php

namespace App\Filament\Resources\Workspaces\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class WorkspaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Workspace Name')
                    ->required()
                    ->maxLength(255),

                Select::make('owner_id')
                    ->label('Owner Company')
                    ->relationship('ownerCompany', 'name')
                    ->required()
                    ->searchable(),
            ]);
    }
}
