<?php

namespace App\Filament\Resources\Contracts\RelationManagers;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use App\Models\ContractAnnex;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractAnnexesRelationManager extends RelationManager
{
    protected static string $relationship = 'annexes';

    protected static ?string $title = 'Annexes';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('contract_id')
                    ->label('Contract')
                    ->relationship('contract', 'contract_number') // or display column
                    ->required()
                    ->default(fn() => request()->integer('contract_id'))
                    ->disabled(fn() => request()->filled('contract_id')) // user can’t change it
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
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')->label('Name')->required()->columns(1),
                        TextInput::make('unit_of_measure')->label('Unit')->placeholder('pcs, h, m²')->columnSpan(1),
//                        TextInput::make('quantity')
//                            ->numeric()->minValue(0)->step('0.01')->required()->columnSpan(3)
//                            ->live()
//                            ->afterStateUpdated(function (Set $set, Get $get) {
//                                $set('line_total', (float)$get('quantity') * (float)$get('unit_price'));
//                            }),
                        TextInput::make('price_per_unit_of_measure')
                            ->numeric()->minValue(0)->step('0.01')->required()->columnSpan(1)
                            ->prefix('€')
                            ->live(onBlur: true) // or ->live(debounce: 600)
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('line_total', (float)$get('quantity') * (float)$get('price_per_unit_of_measure'));
                            }),
//                        TextInput::make('line_total')
//                            ->numeric()->prefix('€')->disabled()
//                            ->dehydrated(false) // display-only
//                            ->columnSpan(3),
                    ])
                    ->collapsed(false)
                    ->collapsible(false)
                    ->cloneable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('annex_number')
            ->columns([
                TextColumn::make('annex_number')
                    ->label('Annex Nr.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sign_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('notes')
            ])
            ->headerActions([
                Action::make('create')
                    ->icon('heroicon-o-plus')
                    ->label('Create Contract Annex')
                    ->color('create')
                    ->url(fn() => ContractAnnexResource::getUrl('create') . '?' . http_build_query([
                            'contract_id' => $this->getOwnerRecord()->getKey(),
                        ])),
            ])
            ->recordActions([
                ViewAction::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn(ContractAnnex $record) => ContractAnnexResource::getUrl('view', ['record' => $record])),
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(ContractAnnex $record) => ContractAnnexResource::getUrl('edit', ['record' => $record]))
                    ->color('edit'),

                DeleteAction::make('delete')
            ]);
    }
}


