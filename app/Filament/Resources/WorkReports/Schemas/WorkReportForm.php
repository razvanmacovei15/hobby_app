<?php

namespace App\Filament\Resources\WorkReports\Schemas;

use App\Enums\WorkReportStatus;
use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractedService;
use App\Models\WorkReportExtraService;
use App\Services\IWorkReportService;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class WorkReportForm
{
    private static function isApproved($record): bool
    {
        return $record && $record->status === WorkReportStatus::APPROVED;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('executor_id')
                    ->live()
                    ->relationship(
                        name: 'executor',
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
                    ->disabled(fn($record) => self::isApproved($record))
                    ->columnSpan(2),
                Select::make('report_month')
                    ->required()
                    ->default(now()->month)
                    ->disabled(fn($record) => self::isApproved($record))
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
                    ->numeric()
                    ->disabled(fn($record) => self::isApproved($record)),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->disabled(fn($record) => self::isApproved($record)),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        WorkReportStatus::DRAFT->value => WorkReportStatus::DRAFT->label(),
                        WorkReportStatus::PENDING_APPROVAL->value => WorkReportStatus::PENDING_APPROVAL->label(),
                    ])
                    ->default(WorkReportStatus::DRAFT->value)
                    ->required()
                    ->disabled(fn($record) => self::isApproved($record)),

                Repeater::make('entries')
                    ->relationship('entries')
                    ->label('Entries')
                    ->defaultItems(1)
                    ->addActionLabel('Add entry')
                    ->columns(6)
                    ->reorderable()
                    ->orderColumn('order')
                    ->visible(fn() => auth()->user()->can('contracted-services.view'))
                    ->addable(fn($record) => auth()->user()->can('contracted-services.create') && !self::isApproved($record))
                    ->reorderable(fn($record) => auth()->user()->can('contracted-services.edit') && !self::isApproved($record))
                    ->deletable(fn($record) => auth()->user()->can('contracted-services.delete') && !self::isApproved($record))
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        $data['service_type'] = ContractedService::class;
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        $data['service_type'] = $data['service_type'] ?? ContractedService::class;
                        return $data;
                    })
                    ->table([
                        TableColumn::make('Service')->width('30%'),
                        TableColumn::make('Unit')->width('10%'),
                        TableColumn::make('Price')->width('20%'),
                        TableColumn::make('Quantity')->width('10%'),
                        TableColumn::make('Total')->width('10%'),
                    ])
                    ->schema([
                        // Hidden field to store service type (ContractedService for now)
                        Hidden::make('service_type')
                            ->default(ContractedService::class)
                            ->hidden()
                            ->dehydrated(true),

                        Select::make('service_id')
                            ->label('Service')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->disabled(fn(Get $get, $record) => !$get('../../executor_id') || self::isApproved($record) || !auth()->user()->can('contracted-services.edit'))
                            ->options(function (Get $get) {
                                $svc = app(IWorkReportService::class);

                                $executorId = (int)$get('../../executor_id');
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

                                // Get the unit of measure for the selected ContractedService
                                $svc = app(IWorkReportService::class);

                                $uom = $svc->getServiceUnitOfMeasure($state);
                                $price = $svc->getPricePerUnit($state);

                                $set('unit_of_measure', $uom ?? '-');
                                $set('price_per_unit_of_measure', $price ?? '');
                            })
                            ->afterStateHydrated(function ($state, Set $set, Get $get) {
                                if (! $state) return;

                                $svc   = app(IWorkReportService::class);
                                $uom   = $svc->getServiceUnitOfMeasure($state);
                                $price = $svc->getPricePerUnit($state);

                                $set('unit_of_measure', $uom ?? '-');
                                $set('price_per_unit_of_measure', $price ?? '');

                                $qty   = (float) ($get('quantity') ?? 0);
                                $set('total', round($qty * (float) ($price ?? 0), 2));
                            })
                            ->columnSpan(2),

                        TextInput::make('unit_of_measure')
                            ->label('Unit')
                            ->disabled()
                            ->dehydrated(true)
                            ->columnSpan(1),

                        TextInput::make('price_per_unit_of_measure')
                            ->label('Price')
                            ->disabled()
                            ->dehydrated(true)
                            ->columnSpan(1)
                            ->suffix(function (Get $get) {
                                if (! $get('service_id')) {
                                    return null; // hide suffix when nothing selected
                                }

//                                $svc = app(\App\Services\IWorkReportService::class);
//                                return $svc->getCurrency(); // e.g. "RON", "EUR"
                                return 'RON';
                            }),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true) // <-- only send update on blur
                            ->disabled(fn (Get $get, $record) => !$get('service_id') || self::isApproved($record) || !auth()->user()->can('contracted-services.edit'))
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $qty   = (float) ($state ?? 0);
                                $price = (float) ($get('price_per_unit_of_measure') ?? 0);
                                $total = round($qty * $price, 2);
                                $set('total', $total); // <- float
                            })
                            ->columnSpan(1),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->disabled()        // read-only to user
                            ->dehydrated()      // still save to DB
                            ->columnSpan(1),
                    ])
                    ->columnSpan(2)
                    ->collapsed(false)
            ]);
    }
}
