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
                TextEntry::make('executor.email')->label('Email'),
            ])->columns(3),

            Section::make('Address')->schema([
                TextEntry::make('executor.address.street')->label('Street'),
                TextEntry::make('executor.address.street_number')->label('No.'),
                TextEntry::make('executor.address.building')->label('Building'),
                TextEntry::make('executor.address.apartment_number')->label('Apt'),
                TextEntry::make('executor.address.city')->label('City'),
                TextEntry::make('executor.address.state')->label('State'),
                TextEntry::make('executor.address.country')->label('Country'),
            ])->columns(4),

            Section::make('Representative')->schema([
                TextEntry::make('representative_name')
                    ->label('Name')
                    ->state(fn ($record) => $record?->executor?->representative
                        ? $record->executor->representative->getFilamentName()
                        : '—'),

                TextEntry::make('executor.representative.email')->label('Email')
            ])->columns(2),

            Section::make('Workspace')->schema([
                TextEntry::make('executor_type')->label('Service Type')->badge(),
                IconEntry::make('has_contract')->label('Does Executor Have A Contract')->boolean(),
            ])->columns(4),
        ])
            ->columns(1);
    }
}
