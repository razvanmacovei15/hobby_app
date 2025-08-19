<?php

namespace App\Filament\Resources\ContractAnnexes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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

                Repeater::make('services')
                    ->label('Services')
                    ->relationship('services') // hasMany on ContractAnnex
                    ->defaultItems(1)
                    ->addActionLabel('Add service')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')->label('Name')->required()->columnSpan(4),
                        TextInput::make('unit')->label('Unit')->placeholder('pcs, h, m²')->columnSpan(2),
                        TextInput::make('quantity')
                            ->numeric()->minValue(0)->step('0.01')->required()->columnSpan(3)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('line_total', (float)$get('quantity') * (float)$get('unit_price'));
                            }),
                        TextInput::make('unit_price')
                            ->numeric()->minValue(0)->step('0.01')->required()->columnSpan(3)
                            ->prefix('€')
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('line_total', (float)$get('quantity') * (float)$get('unit_price'));
                            }),
                        TextInput::make('line_total')
                            ->numeric()->prefix('€')->disabled()
                            ->dehydrated(false) // display-only
                            ->columnSpan(3),
                        TextInput::make('notes')->columnSpan(12),
                    ])
                    ->collapsed(false)
                    ->cloneable(),
            ]);
    }
}
