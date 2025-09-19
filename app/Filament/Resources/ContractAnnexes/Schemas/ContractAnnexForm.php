<?php

namespace App\Filament\Resources\ContractAnnexes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
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
                    ->dehydrated()->disabledOn('edit'), // still saves when disabled

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
                    ->columns(3)
                    ->reorderable(true)
                    ->orderColumn('sort_order')
                    ->table([
//                        TableColumn::make('Order')->width('10%'),
                        TableColumn::make('Name')->width('60%'),
                        TableColumn::make('Unit')->width('10%'),
                        TableColumn::make('Price')->width('20%'),
                    ])
                    ->schema([
//                        TextInput::make('sort_order')
//                            ->hidden()
//                            ->dehydrated(),
                        TextInput::make('name')->label('Name')->required()->columns(1),
                        TextInput::make('unit_of_measure')->label('Unit')->placeholder('pcs, h, m²')->columnSpan(1),
                        TextInput::make('price_per_unit_of_measure')
                            ->numeric()->minValue(0)->step('0.01')->required()->columnSpan(1)
                            ->suffix('RON')
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('line_total', (float)$get('quantity') * (float)$get('price_per_unit_of_measure'));
                            }),
                    ])
                    ->collapsed(false),
            ]);
    }
}
