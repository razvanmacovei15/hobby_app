<?php

namespace App\Filament\Resources\WorkReportResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_type')
                    ->label('Tip Serviciu')
                    ->options([
                        'App\\Models\\ContractService' => 'Serviciu Contract',
                        'App\\Models\\ContractExtraService' => 'Serviciu Extra',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('service_id', null)),
                    
                Forms\Components\Select::make('service_id')
                    ->label('Serviciu')
                    ->options(function (callable $get) {
                        $serviceType = $get('service_type');
                        
                        if (!$serviceType) {
                            return [];
                        }
                        
                        if ($serviceType === 'App\\Models\\ContractService') {
                            return \App\Models\ContractService::all()->pluck('name', 'id');
                        }
                        
                        if ($serviceType === 'App\\Models\\ContractExtraService') {
                            return \App\Models\ContractExtraService::all()->pluck('name', 'id');
                        }
                        
                        return [];
                    })
                    ->required()
                    ->searchable(),
                    
                Forms\Components\TextInput::make('order')
                    ->label('Ordine')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantitate')
                    ->numeric()
                    ->step(0.01)
                    ->required(),
                    
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->step(0.01)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordine')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Tip Serviciu')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'App\\Models\\ContractService' => 'Serviciu Contract',
                        'App\\Models\\ContractExtraService' => 'Serviciu Extra',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Serviciu')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantitate')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('RON')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_type')
                    ->label('Tip Serviciu')
                    ->options([
                        'App\\Models\\ContractService' => 'Serviciu Contract',
                        'App\\Models\\ContractExtraService' => 'Serviciu Extra',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }
} 