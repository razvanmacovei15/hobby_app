<?php

namespace App\Filament\Resources\BuildingPermits\Schemas;

use App\Enums\PermitStatus;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class BuildingPermitInfolist
{
    public static function configure(Schema $infolist): Schema
    {
        return $infolist
            ->components([
                Tabs::make('Building Permit Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Permit Details')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->columnSpanFull(),

                                        TextEntry::make('display_name')
                                            ->label('Permit Number')
                                            ->getStateUsing(fn($record) => $record ? $record->permit_number . '/' . $record->issuance_year : "—"),

                                        TextEntry::make('permit_type')
                                            ->label('Permit Type')
                                            ->badge(),

                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->color(fn ($state) => match ($state) {
                                                PermitStatus::PENDING => 'warning',
                                                PermitStatus::APPROVED => 'success',
                                                PermitStatus::REJECTED => 'danger',
                                                PermitStatus::EXPIRED => 'gray',
                                                PermitStatus::REVOKED => 'danger',
                                                default => 'gray',
                                            }),

                                        TextEntry::make('workspace.name')
                                            ->label('Workspace'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Construction Details')
                            ->schema([
                                Section::make('Project Information')
                                    ->schema([
                                        TextEntry::make('architect'),

                                        TextEntry::make('height_regime')
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('land_book_number')
                                            ->label('Land Book Number'),

                                        TextEntry::make('cadastral_number')
                                            ->label('Cadastral Number'),
                                    ])
                                    ->columns(2),

                                Section::make('Address Information')
                                    ->schema([
                                        TextEntry::make('address.full_address')
                                            ->label('Full Address')
                                            ->columnSpanFull(),

                                        TextEntry::make('address.city')
                                            ->label('City'),

                                        TextEntry::make('address.state')
                                            ->label('County'),

                                        TextEntry::make('address.country')
                                            ->label('Country'),
                                    ])
                                    ->columns(3),
                            ]),

                        Tabs\Tab::make('Timeline & Media')
                            ->schema([
                                Section::make('Timeline')
                                    ->schema([
                                        TextEntry::make('work_start_date')
                                            ->label('Work Start Date')
                                            ->date(),

                                        TextEntry::make('execution_duration_days')
                                            ->label('Duration')
                                            ->formatStateUsing(fn($state) => $state ? $state . ' days' : '—'),

                                        TextEntry::make('work_end_date')
                                            ->label('Work End Date')
                                            ->date(),

                                        TextEntry::make('validity_term')
                                            ->label('Validity Term')
                                            ->date()
                                            ->color(fn($state) => $state && \Carbon\Carbon::parse($state)->isPast() ? 'danger' : 'success'),
                                    ])
                                    ->columns(2),

                                Section::make('Media')
                                    ->schema([
                                        ImageEntry::make('image_url')
                                            ->label('Project Image')
                                            ->defaultImageUrl(url('images/construction-placeholder.png'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
