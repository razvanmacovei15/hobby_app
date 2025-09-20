<?php

namespace App\Filament\Pages;

use App\Enums\PermitStatus;
use App\Models\BuildingPermit;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class BuildingPermitPage extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected string $view = 'filament.pages.building-permit-page';
    protected static string|null|\UnitEnum $navigationGroup = 'Workspace';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Building Permit';
    protected static ?string $title = 'Building Permit';

    public ?BuildingPermit $record = null;

    public function mount(): void
    {
        $workspace = Filament::getTenant();
        if ($workspace) {
            $this->record = $workspace->buildingPermit;
        }
    }

    public function buildingPermitInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->record)
            ->components([
                Tabs::make('Building Permit Details')
                    ->vertical()
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Permit Details')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->state(fn($record) => $record->name ?? "—")
                                            ->columnSpanFull(),

                                        TextEntry::make('display_name')
                                            ->label('Permit Number')
                                            ->state(fn($record) => $record ? $record->permit_number . '/' . $record->issuance_year : "—"),

                                        TextEntry::make('permit_type')
                                            ->label('Permit Type')
                                            ->state(fn($record) => $record->permit_type?->label() ?? "—")
                                            ->badge(),

                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->state(fn($record) => $record->status?->label() ?? "—")
                                            ->badge()
                                            ->color(fn ($record) => match ($record?->status) {
                                                PermitStatus::PENDING => 'warning',
                                                PermitStatus::APPROVED => 'success',
                                                PermitStatus::REJECTED => 'danger',
                                                PermitStatus::EXPIRED => 'gray',
                                                PermitStatus::REVOKED => 'danger',
                                                default => 'gray',
                                            }),

                                        TextEntry::make('workspace.name')
                                            ->label('Workspace')
                                            ->state(fn($record) => $record->workspace?->name ?? "—"),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Construction Details')
                            ->schema([
                                Section::make('Project Information')
                                    ->schema([
                                        TextEntry::make('architect')
                                            ->state(fn($record) => $record->architect ?? "—"),

                                        TextEntry::make('height_regime')
                                            ->state(fn($record) => $record->height_regime ?? "—")
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('land_book_number')
                                            ->label('Land Book Number')
                                            ->state(fn($record) => $record->land_book_number ?? "—"),

                                        TextEntry::make('cadastral_number')
                                            ->label('Cadastral Number')
                                            ->state(fn($record) => $record->cadastral_number ?? "—"),
                                    ])
                                    ->columns(2),

                                Section::make('Address Information')
                                    ->schema([
                                        TextEntry::make('address.full_address')
                                            ->label('Full Address')
                                            ->state(fn($record) => $record->address?->full_address ?? "—")
                                            ->columnSpanFull(),

                                        TextEntry::make('address.city')
                                            ->label('City')
                                            ->state(fn($record) => $record->address?->city ?? "—"),

                                        TextEntry::make('address.state')
                                            ->label('County')
                                            ->state(fn($record) => $record->address?->state ?? "—"),

                                        TextEntry::make('address.country')
                                            ->label('Country')
                                            ->state(fn($record) => $record->address?->country ?? "—"),
                                    ])
                                    ->columns(3),
                            ]),

                        Tabs\Tab::make('Timeline & Media')
                            ->schema([
                                Section::make('Timeline')
                                    ->schema([
                                        TextEntry::make('work_start_date')
                                            ->label('Work Start Date')
                                            ->state(fn($record) => $record->work_start_date ? $record->work_start_date->format('Y-m-d') : "—")
                                            ->date(),

                                        TextEntry::make('execution_duration_days')
                                            ->label('Duration')
                                            ->state(fn($record) => $record->execution_duration_days ? $record->execution_duration_days . ' days' : "—"),

                                        TextEntry::make('work_end_date')
                                            ->label('Work End Date')
                                            ->state(fn($record) => $record->work_end_date ? $record->work_end_date->format('Y-m-d') : "—")
                                            ->date(),

                                        TextEntry::make('validity_term')
                                            ->label('Validity Term')
                                            ->state(fn($record) => $record->validity_term ? $record->validity_term->format('Y-m-d') : "—")
                                            ->date()
                                            ->color(fn($record) => $record && $record->validity_term && $record->validity_term->isPast() ? 'danger' : 'success'),
                                    ])
                                    ->columns(2),

                                Section::make('Media')
                                    ->schema([
                                        ImageEntry::make('image_url')
                                            ->label('Project Image')
                                            ->state(fn($record) => $record->image_url)
                                            ->defaultImageUrl(url('images/construction-placeholder.png'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ])
            ->columns(1);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canInWorkspace('building-permit-page.view') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
