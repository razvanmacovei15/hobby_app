<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkReportResource\Pages;
use App\Filament\Resources\WorkReportResource\RelationManagers;
use App\Models\WorkReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkReportResource extends Resource
{
    protected static ?string $model = WorkReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Situatii de Lucrari';

    protected static ?string $modelLabel = 'Situatie de Lucrari';

    protected static ?string $pluralModelLabel = 'Situatii de Lucrari';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informații Raport')
                    ->schema([
                        Forms\Components\Select::make('contract_id')
                            ->label('Contract')
                            ->relationship('contract', 'contract_number')
                            ->required()
                            ->preload()
                            ->searchable(),

                        Forms\Components\Select::make('written_by')
                            ->label('Scris de')
                            ->relationship('writtenBy', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->getFilamentName())
                            ->required()
                            ->searchable(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('report_month')
                                    ->label('Luna')
                                    ->options([
                                        'ianuarie' => 'Ianuarie',
                                        'februarie' => 'Februarie',
                                        'martie' => 'Martie',
                                        'aprilie' => 'Aprilie',
                                        'mai' => 'Mai',
                                        'iunie' => 'Iunie',
                                        'iulie' => 'Iulie',
                                        'august' => 'August',
                                        'septembrie' => 'Septembrie',
                                        'octombrie' => 'Octombrie',
                                        'noiembrie' => 'Noiembrie',
                                        'decembrie' => 'Decembrie',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('report_year')
                                    ->label('Anul')
                                    ->numeric()
                                    ->default(date('Y'))
                                    ->required(),
                            ]),

                        Forms\Components\TextInput::make('report_number')
                            ->label('Nr. Raport')
                            ->numeric()
                            ->disabled()
                            ->helperText('Numărul raportului se generează automat'),

                        Forms\Components\Textarea::make('observations')
                            ->label('Observații')
                            ->rows(3)
                            ->placeholder('Observații despre lucrările efectuate...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Înregistrări Raport')
                    ->schema([
                        Forms\Components\Repeater::make('entries')
                            ->relationship('entries')
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
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['service_type'] === 'App\\Models\\ContractService'
                                    ? \App\Models\ContractService::find($state['service_id'])?->name
                                    : \App\Models\ContractExtraService::find($state['service_id'])?->name
                            ),
                    ])
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_number')
                    ->label('Nr. Raport')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('contract.contract_number')
                    ->label('Contract')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('report_month')
                    ->label('Luna')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('report_year')
                    ->label('Anul')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('executor.name')
                    ->label('Executant')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('beneficiary.name')
                    ->label('Beneficiar')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('writtenBy.first_name')
                    ->label('Scris de')
                    ->formatStateUsing(fn ($state, $record) => $record->writtenBy ? $record->writtenBy->getFilamentName() : '')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('entries_count')
                    ->label('Nr. Înregistrări')
                    ->counts('entries')
                    ->sortable(),

                Tables\Columns\TextColumn::make('observations')
                    ->label('Observații')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creat la')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('report_year')
                    ->label('Anul')
                    ->options([
                        2024 => '2024',
                        2025 => '2025',
                        2026 => '2026',
                    ]),

                Tables\Filters\SelectFilter::make('report_month')
                    ->label('Luna')
                    ->options([
                        'ianuarie' => 'Ianuarie',
                        'februarie' => 'Februarie',
                        'martie' => 'Martie',
                        'aprilie' => 'Aprilie',
                        'mai' => 'Mai',
                        'iunie' => 'Iunie',
                        'iulie' => 'Iulie',
                        'august' => 'August',
                        'septembrie' => 'Septembrie',
                        'octombrie' => 'Octombrie',
                        'noiembrie' => 'Noiembrie',
                        'decembrie' => 'Decembrie',
                    ]),

                Tables\Filters\SelectFilter::make('contract_id')
                    ->label('Contract')
                    ->relationship('contract', 'contract_number'),

                Tables\Filters\SelectFilter::make('written_by')
                    ->label('Scris de')
                    ->relationship('writtenBy', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getFilamentName()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('entries')
                    ->label('Înregistrări')
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn (WorkReport $record): string => route('filament.admin.resources.work-reports.edit', ['record' => $record, 'activeTab' => 'entries'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkReports::route('/'),
            'create' => Pages\CreateWorkReport::route('/create'),
            'edit' => Pages\EditWorkReport::route('/{record}/edit'),
        ];
    }
}
