<?php

namespace App\Filament\Pages;

use App\Enums\PermitStatus;
use App\Models\BuildingPermit;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
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
                Section::make('Permit Details')->schema([
                    TextEntry::make('permit_number')->label('Permit Number')->state(fn($record) => $record->permit_number ?? "—"),
                    TextEntry::make('permit_type')->label('Permit Type')->state(fn($record) => $record->permit_type?->label() ?? "—"),
                    TextEntry::make('status')->label('Status')->state(fn($record) => $record->status?->label() ?? "—")
                        ->badge()
                        ->color(fn ($record) => match ($record?->status) {
                            PermitStatus::PENDING=> 'warning',
                            PermitStatus::APPROVED => 'success',
                            \App\Enums\PermitStatus::REJECTED => 'danger',
                            \App\Enums\PermitStatus::EXPIRED => 'gray',
                            \App\Enums\PermitStatus::REVOKED => 'danger',
                            default => 'gray',
                        }),
                ])->columns(3),
            ])
            ->columns(1);
    }

    protected function getHeaderActions(): array
    {
        return [
//            Action::make('edit')
//                ->label($this->record ? 'Edit Permit' : 'Create Permit')
//                ->icon('heroicon-o-pencil')
//                ->url(fn () => EditBuildingPermitPage::getUrl()),
        ];
    }
}
