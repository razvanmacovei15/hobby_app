<?php

namespace App\Filament\Resources\WorkReports\Schemas;

use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractService;
use App\Models\WorkReportExtraService;
use App\Services\IWorkReportService;
use Filament\Facades\Filament;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class WorkReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->live()
                    ->relationship(
                        name: 'company',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $svc = app(IWorkReportService::class);

                            // Get executor company IDs directly from the service
                            $executorIds = $svc->getAllExecutorsForThisWorkspace();

                            // Use the qualified PK for safety
                            $qualifiedKey = $query->getModel()->getQualifiedKeyName();

                            $query->whereIn($qualifiedKey, $executorIds ?: [0]); // [0] returns no rows when empty
                        }
                    )
                    ->required()
                    ->columnSpan(2),
                Select::make('report_month')
                    ->required()
                    ->default(now()->month)
                    ->options([
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ]),
                TextInput::make('report_year')
                    ->required()
                    ->default(now()->format('Y'))
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),

                Repeater::make('entries')
                    ->relationship('entries')
                    ->label('Entries')
                    ->defaultItems(1)
                    ->addActionLabel('Add entry')
                    ->columns(6)
                    ->reorderable()
                    ->orderColumn('order')
                    ->schema([
                        // Hidden field to store service type (ContractService for now)
                        TextInput::make('service_type')
                            ->default('App\\Models\\ContractService')
                            ->hidden()
                            ->dehydrated(),

                        Select::make('service_id')
                            ->label('Service')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->disabled(fn(Get $get) => !$get('../../company_id'))
                            ->options(function (Get $get) {
                                $svc = app(IWorkReportService::class);

                                $executorId = (int)$get('../../company_id');
                                if (!$executorId) {
                                    return [];
                                }

                                $contractId = (int)$svc->getContractIdFromWorkSpaceOwner($executorId);

                                if (!$contractId) {
                                    return [];
                                }

                                return $svc->getAllServicesForThisContract($contractId);
                            })
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                // Clear when nothing selected
                                if (!$state) {
                                    $set('unit_of_measure', null);
                                    $set('price_per_unit_of_measure', null);
                                    return;
                                }

                                // Get the unit of measure for the selected ContractService
                                $svc = app(IWorkReportService::class);

                                $uom = $svc->getServiceUnitOfMeasure($state);
                                $price = $svc->getPricePerUnit($state);

                                $set('unit_of_measure', $uom ?? '-');
                                $set('price_per_unit_of_measure', $price ?? '');
                            })
                            ->columnSpan(2),

                        TextInput::make('unit_of_measure')
                            ->label('Unit')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        TextInput::make('price_per_unit_of_measure')
                            ->label('Price')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('notes')
                            ->label('Notes')
                            ->columnSpan(1),
                    ])
                    ->columnSpan(2)
                    ->collapsed(false)
            ]);
    }
}
