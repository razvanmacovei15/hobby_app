<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('contract_number')
                    ->required(),
                DatePicker::make('sign_date')
                    ->required()
                    ->native(false),
                DatePicker::make('start_date')
                    ->required()
                    ->native(false),
                DatePicker::make('end_date')
                    ->required()
                    ->native(false),
                Select::make('executor_id')
                    ->relationship('executor', 'name')
                    ->required()
                    ->default(fn() => request()->query('executor_id')),
            ]);
    }
}
