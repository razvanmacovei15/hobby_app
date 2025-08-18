<?php

namespace App\Filament\Resources\ContractAnnexes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContractAnnexForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('contract_id')
                    ->relationship('contract', 'id')
                    ->required(),
                TextInput::make('annex_number')
                    ->required()
                    ->numeric(),
                DatePicker::make('sign_date')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
