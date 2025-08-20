<?php

namespace App\Filament\Resources\ContractAnnexes\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';
    protected static ?string $title = 'Services';

    // Show this relation ONLY on the Annex View page (not on Edit/Create)
    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return is_subclass_of($pageClass, ViewRecord::class);
    }

    // Keep it read-only on the View page
    public function isReadOnly(): bool
    {
        return false;
    }

//    public function form(Schema $schema): Schema
//    {
//        return $schema
//            ->components([
//                TextInput::make('sort_order')
//                    ->required()
//                    ->numeric()
//                    ->default(0),
//                TextInput::make('name')
//                    ->required(),
//                TextInput::make('unit_of_measure')
//                    ->required(),
//                TextInput::make('price_per_unit_of_measure')
//                    ->required()
//                    ->numeric(),
//            ]);
//    }
//
//    public function infolist(Schema $schema): Schema
//    {
//        return $schema
//            ->components([
//                TextEntry::make('sort_order')
//                    ->numeric(),
//                TextEntry::make('name'),
//                TextEntry::make('unit_of_measure'),
//                TextEntry::make('price_per_unit_of_measure')
//                    ->numeric(),
//                TextEntry::make('created_at')
//                    ->dateTime(),
//                TextEntry::make('updated_at')
//                    ->dateTime(),
//            ]);
//    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_of_measure')
                    ->searchable(),
                TextColumn::make('price_per_unit_of_measure')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([
                DeleteAction::make('delete'),
            ])
            ->toolbarActions([

            ]);
    }
}
