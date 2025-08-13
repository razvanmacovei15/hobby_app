<?php

namespace App\Filament\Resources\Executors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExecutorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Company Details')->schema([
                TextEntry::make('executor.name')->label('Company Name'),
                TextEntry::make('executor.cui')->label('CUI'),
                TextEntry::make('executor.j')->label('J'),
                TextEntry::make('executor.place_of_registration')->label('Place of Registration'),
                TextEntry::make('executor.iban')->label('IBAN'),
                TextEntry::make('executor.phone')->label('Phone'),
            ])->columns(3),

            Section::make('Workspace')->schema([
                TextEntry::make('workspace.name')->label('Workspace'),
                IconEntry::make('is_active')->label('Is Executor Active')->boolean(),
            ]),
        ])
            ->columns(1);
    }
}
