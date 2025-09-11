<?php

namespace App\Filament\Resources\BuildingPermits\Schemas;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class BuildingPermitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Building Permit Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Permit Details')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('permit_number')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),

                                        TextInput::make('issuance_year')
                                            ->required()
                                            ->numeric()
                                            ->default(now()->year)
                                            ->minValue(2000)
                                            ->maxValue(2030),

                                        Select::make('permit_type')
                                            ->options(PermitType::class)
                                            ->required(),

                                        Select::make('status')
                                            ->options(PermitStatus::class)
                                            ->default(PermitStatus::PENDING)
                                            ->required(),

                                        Select::make('workspace_id')
                                            ->relationship('workspace', 'name')
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Construction Details')
                            ->schema([
                                Section::make('Project Information')
                                    ->schema([
                                        TextInput::make('architect')
                                            ->maxLength(255),

                                        TextInput::make('height_regime')
                                            ->label('Height Regime')
                                            ->placeholder('e.g., P, P+1, P+2, etc.')
                                            ->maxLength(255),

                                        TextInput::make('land_book_number')
                                            ->maxLength(255),

                                        TextInput::make('cadastral_number')
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),

                                Section::make('Address Information')
                                    ->schema([
                                        TextInput::make('address.street')
                                            ->label('Street')
                                            ->maxLength(255),

                                        TextInput::make('address.street_number')
                                            ->label('Street Number')
                                            ->maxLength(255),

                                        TextInput::make('address.city')
                                            ->label('City')
                                            ->maxLength(255),

                                        TextInput::make('address.building')
                                            ->label('Building')
                                            ->maxLength(255),

                                        TextInput::make('address.apartment_number')
                                            ->label('Apartment Number')
                                            ->maxLength(255),

                                        Select::make('address.state')
                                            ->label('County')
                                            ->options([
                                                'Alba' => 'Alba',
                                                'Arad' => 'Arad',
                                                'Argeș' => 'Argeș',
                                                'Bacău' => 'Bacău',
                                                'Bihor' => 'Bihor',
                                                'Bistrița-Năsăud' => 'Bistrița-Năsăud',
                                                'Botoșani' => 'Botoșani',
                                                'Brașov' => 'Brașov',
                                                'Brăila' => 'Brăila',
                                                'București' => 'București',
                                                'Buzău' => 'Buzău',
                                                'Caraș-Severin' => 'Caraș-Severin',
                                                'Cluj' => 'Cluj',
                                                'Constanța' => 'Constanța',
                                                'Covasna' => 'Covasna',
                                                'Dâmbovița' => 'Dâmbovița',
                                                'Dolj' => 'Dolj',
                                                'Galați' => 'Galați',
                                                'Giurgiu' => 'Giurgiu',
                                                'Gorj' => 'Gorj',
                                                'Harghita' => 'Harghita',
                                                'Hunedoara' => 'Hunedoara',
                                                'Ialomița' => 'Ialomița',
                                                'Iași' => 'Iași',
                                                'Ilfov' => 'Ilfov',
                                                'Maramureș' => 'Maramureș',
                                                'Mehedinți' => 'Mehedinți',
                                                'Mureș' => 'Mureș',
                                                'Neamț' => 'Neamț',
                                                'Olt' => 'Olt',
                                                'Prahova' => 'Prahova',
                                                'Satu Mare' => 'Satu Mare',
                                                'Sălaj' => 'Sălaj',
                                                'Sibiu' => 'Sibiu',
                                                'Suceava' => 'Suceava',
                                                'Teleorman' => 'Teleorman',
                                                'Timiș' => 'Timiș',
                                                'Tulcea' => 'Tulcea',
                                                'Vaslui' => 'Vaslui',
                                                'Vâlcea' => 'Vâlcea',
                                                'Vrancea' => 'Vrancea',
                                            ])
                                            ->searchable(),

                                        TextInput::make('address.country')
                                            ->label('Country')
                                            ->default('Romania')
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Timeline & Media')
                            ->schema([
                                Section::make('Timeline')
                                    ->schema([
                                        DatePicker::make('work_start_date')
                                            ->native(false)
                                            ->placeholder('Select work start date')
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $duration = $get('execution_duration_days');
                                                if ($state && $duration) {
                                                    $endDate = \Carbon\Carbon::parse($state)->addDays($duration);
                                                    $set('work_end_date', $endDate->toDateString());
                                                }
                                            }),

                                        TextInput::make('execution_duration_days')
                                            ->numeric()
                                            ->suffix('days')
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $startDate = $get('work_start_date');
                                                if ($startDate && $state) {
                                                    $endDate = \Carbon\Carbon::parse($startDate)->addDays($state);
                                                    $set('work_end_date', $endDate->toDateString());
                                                }
                                            }),

                                        DatePicker::make('work_end_date')
                                            ->native(false)
                                            ->placeholder('Auto-calculated from start date + duration')
                                            ->disabled(),

                                        DatePicker::make('validity_term')
                                            ->native(false)
                                            ->placeholder('Select permit validity end date'),
                                    ])
                                    ->columns(2),

                                Section::make('Media')
                                    ->schema([
                                        FileUpload::make('image_url')
                                            ->image()
                                            ->imageEditor()
                                            ->maxSize(5120)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
