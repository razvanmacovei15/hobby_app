<?php

namespace App\Filament\Resources\WorkspaceUsers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WorkspaceUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                Select::make('workspace_id')
                    ->relationship('workspace', 'name')
                    ->required(),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}
