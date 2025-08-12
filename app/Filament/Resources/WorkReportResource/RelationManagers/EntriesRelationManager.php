<?php

namespace App\Filament\Resources\WorkReportResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Services\IWorkReportService;
use App\Enums\ServiceType;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'App\\Models\\ContractService' => 'Contract Service',
                        'App\\Models\\ContractExtraService' => 'Extra Service',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('service_id', null)),

                Forms\Components\Select::make('service_id')
                    ->label('Service')
//                    ->relationship('service', 'service_id')
                    ->options(function (callable $get) {
                        $serviceType = $get('service_type');
                        $companyId = $this->getOwnerRecord()->company_id;

                        if (!$serviceType || !$companyId) {
                            return [];
                        }

                        $workReportEntryService = app(IWorkReportService::class);

                        $enumServiceType = match($serviceType) {
                            'App\\Models\\ContractService' => ServiceType::CONTRACT_SERVICE,
                            'App\\Models\\ContractExtraService' => ServiceType::CONTRACT_EXTRA_SERVICE,
                            default => null,
                        };

                        if (!$enumServiceType) {
                            return [];
                        }

                        $services = $workReportEntryService->getServices($enumServiceType, $companyId);

                        return $services->pluck('name', 'id');
                    })
//                    ->createOptionForm([
//                        Forms\Components\TextInput::make('name')
//                            ->required(),
//                            ])
                    ->required()
                    ->searchable()
                    ->reactive(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
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
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('service_type')
                    ->label('Service Type')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'App\\Models\\ContractService' => 'Contract Service',
                        'App\\Models\\ContractExtraService' => 'Extra Service',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('RON')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'App\\Models\\ContractService' => 'Contract Service',
                        'App\\Models\\ContractExtraService' => 'Extra Service',
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
