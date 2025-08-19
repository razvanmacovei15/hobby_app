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
                    ->label('Contract')
                    ->relationship('contract', 'contract_number') // or display column
                    ->required()
                    ->default(fn () => request()->integer('contract_id'))
                    ->disabled(fn () => request()->filled('contract_id')) // user can’t change it
                    ->dehydrated(), // still saves when disabled
                DatePicker::make('sign_date')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
