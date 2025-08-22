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

                            // CORRECT: pluck from companies, not executors
                            $ids = $svc->getAllExecutorsForThisWorkspace()
                                ->pluck('companies.id')
                                ->all();

                            // Use the qualified PK for safety (e.g., "companies.id")
                            $qualifiedKey = $query->getModel()->getQualifiedKeyName();

                            $query->whereIn($qualifiedKey, $ids ?: [0]); // [0] returns no rows when empty
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
                        Select::make('service_id')                 // matches your WorkReportEntry fillable
                        ->label('Service')
                            ->searchable()
                            ->preload()
                            ->live()                              // re-run options when parent state changes
                            ->disabled(fn (Get $get) => ! $get('../../company_id'))
                            ->options(function (Get $get) {
                                $svc = app(IWorkReportService::class);

                                $executorId = (int) $get('../../company_id'); // parent Select on the form
                                if (! $executorId) {
                                    return [];
                                }

                                $contractId = (int) $svc->getContractIdFromWorkSpaceOwner($executorId);

                                if (! $contractId) {
                                    return [];
                                }
                                $beneficiary = Company::findOrFail(Contract::findOrFail($contractId)->beneficiary_id)->id;
                                $executor = Company::findOrFail($executorId)->id;


                                // Pull services via your service method
                                return $svc->getAllServicesForThisContract($contractId); // id => label

                            })
                            ->columnSpan(3),
                        // ... add quantity/price/total fields next
                    ])
                    ->columnSpan(2)
                    ->collapsed(false)
            ]);
    }
}
