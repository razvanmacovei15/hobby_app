<?php

namespace App\Filament\Resources\WorkReports\Schemas;

use App\Services\IWorkReportService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class WorkReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
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
                    ->required(),
                Select::make('written_by')
                    ->relationship('writtenBy', 'first_name')
                    ->required(),
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
            ]);
    }
}
