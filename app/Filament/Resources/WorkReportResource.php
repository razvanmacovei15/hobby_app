<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkReportResource\Pages;
use App\Filament\Resources\WorkReportResource\RelationManagers;
use App\Models\WorkReport;
use App\Service\BookingService;
use App\Services\Implementations\UserService;
use App\Services\Implementations\WorkReportEntryService;
use App\Services\IUserService;
use App\Services\IWorkReportEntryService;
use App\Enums\ServiceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\App;

class WorkReportResource extends Resource
{
    protected static ?string $model = WorkReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Work Reports';

    protected static ?string $modelLabel = 'Work Report';

    protected static ?string $pluralModelLabel = 'Work Reports';

    protected IUserService $userService;
    protected IWorkReportEntryService $workReportEntryService;

    public function __construct()
    {
        $this->initServices();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Report Information')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('written_by')
                            ->label('Written by')
                            ->relationship('writtenBy', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->getFilamentName())
                            ->required()
                            ->default(fn(IUserService $userService) => optional(
                                $userService->getDefaultWorkReportCreator()
                            )->getKey()),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('report_month')
                                    ->label('Month')
                                    ->options([
                                        'january' => 'January',
                                        'february' => 'February',
                                        'march' => 'March',
                                        'april' => 'April',
                                        'may' => 'May',
                                        'june' => 'June',
                                        'july' => 'July',
                                        'august' => 'August',
                                        'september' => 'September',
                                        'october' => 'October',
                                        'november' => 'November',
                                        'december' => 'December',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('report_year')
                                    ->label('Year')
                                    ->numeric()
                                    ->default(date('Y'))
                                    ->required(),
                            ]),

//                        Forms\Components\Textarea::make('observations')
//                            ->label('Observations')
//                            ->columnSpan(2)
//                            ->rows(2)
//                            ->placeholder('Observations about the work performed...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Report Services')
                    ->schema([
                        Forms\Components\Repeater::make('entries')
                            ->relationship('entries')
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
                                    ->relationship('service', 'name')
                                    ->options(function (callable $get, $context) {
                                        $serviceType = $get('service_type');
                                        $companyId = $get('../../company_id');

                                        if (!$serviceType || !$companyId) {
                                            return [];
                                        }

                                        $workReportEntryService = app(IWorkReportEntryService::class);
                                        
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
//                                    ->createOptionForm([
//                                        Forms\Components\TextInput::make('name')
//                                            ->required(),
//                                        ])
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
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['service_type'] === 'App\\Models\\ContractService'
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
                    ->label('Report No.')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('report_month')
                    ->label('Month')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('report_year')
                    ->label('Year')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('writtenBy.first_name')
                    ->label('Written by')
                    ->formatStateUsing(fn($state, $record) => $record->writtenBy ? $record->writtenBy->getFilamentName() : '')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('entries_count')
                    ->label('No. of services')
                    ->counts('entries')
                    ->sortable(),

                Tables\Columns\TextColumn::make('observations')
                    ->label('Observations')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('report_year')
                    ->label('Year')
                    ->options([
                        2024 => '2024',
                        2025 => '2025',
                        2026 => '2026',
                    ]),

                Tables\Filters\SelectFilter::make('report_month')
                    ->label('Month')
                    ->options([
                        'january' => 'January',
                        'february' => 'February',
                        'march' => 'March',
                        'april' => 'April',
                        'may' => 'May',
                        'june' => 'June',
                        'july' => 'July',
                        'august' => 'August',
                        'september' => 'September',
                        'october' => 'October',
                        'november' => 'November',
                        'december' => 'December',
                    ]),

                Tables\Filters\SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name'),

                Tables\Filters\SelectFilter::make('written_by')
                    ->label('Written by')
                    ->relationship('writtenBy', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->getFilamentName()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('entries')
                    ->label('Services')
                    ->icon('heroicon-o-list-bullet')
                    ->url(fn(WorkReport $record): string => route('filament.admin.resources.work-reports.edit', ['record' => $record, 'activeTab' => 'entries'])),
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

    private function initServices(): void
    {
        $this->userService = App::make(UserService::class);
        $this->workReportEntryService = App::make(WorkReportEntryService::class);
    }
}
