<?php

namespace App\Filament\Resources\Contracts\RelationManagers;

use App\Filament\Resources\ContractAnnexes\ContractAnnexResource;
use Filament\Actions\Action;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractAnnexesRelationManager extends RelationManager
{
    protected static string $relationship = 'annexes';

    protected static ?string $title = 'Annexes';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('sign_date')
                ->required(),
            Textarea::make('notes')
                ->columnSpanFull(),
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
                Action::make('Create annex')
                    ->label('Create annex'),
            ]);
    }
}


