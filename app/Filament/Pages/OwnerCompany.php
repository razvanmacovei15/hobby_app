<?php

namespace App\Filament\Pages;

use App\Models\Company;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OwnerCompany extends Page implements HasInfolists
{
    use InteractsWithInfolists;

    protected string $view = 'filament.pages.owner-company';
    protected static string|null|\UnitEnum $navigationGroup = 'Organization';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Owner company';
    protected static ?string $title = 'Owner company';

    public ?Company $record = null;

    public function mount(): void
    {
        $workspace = Filament::getTenant();
        // If no owner yet, just keep $record = null.
        if ($workspace?->owner_id) {
            $this->record = Company::find($workspace->owner_id);
        }
    }

    public function companyInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->record)
            ->components([
                Section::make('Company Details')->schema([
                    TextEntry::make('name')->label('Company Name'),
                    TextEntry::make('cui')->label('CUI'),
                    TextEntry::make('j')->label('J'),
                    TextEntry::make('place_of_registration')->label('Place of Registration'),
                    TextEntry::make('iban')->label('IBAN'),
                    TextEntry::make('phone')->label('Phone'),
                ])->columns(4),

                Section::make('Address')->schema([
                    TextEntry::make('address.street')->label('Street'),
                    TextEntry::make('address.street_number')->label('No.'),
                    TextEntry::make('address.building')->label('Building'),
                    TextEntry::make('address.apartment_number')->label('Apt'),
                    TextEntry::make('address.city')->label('City'),
                    TextEntry::make('address.state')->label('State'),
                    TextEntry::make('address.country')->label('Country'),
                ])->columns(4),

                Section::make('Representative')->schema([
                    TextEntry::make('representative_name')
                        ->label('Name')
                        ->state(fn($record) => $record?->representative
                            ? $record->representative->getFilamentName()
                            : '—'),

                    TextEntry::make('representative.email')->label('Email')
                ])->columns(2),
            ])
            ->columns(1);
    }

    protected function getHeaderActions(): array
    {
        if (! $this->record) {
            // No company yet → show "Create" in header
            return [

            ];
        }

        // Company exists → show "Edit"
        return [
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-o-pencil-square')
                ->url(EditOwnerCompany::getUrl()),
        ];
    }
}
