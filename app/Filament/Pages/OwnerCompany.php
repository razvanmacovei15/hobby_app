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
    protected static string|null|\UnitEnum $navigationGroup = 'Company Management';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Company Details';
    protected static ?string $title = '';

    public ?Company $record = null;

    public function mount(): void
    {
        $workspace = Filament::getTenant();
        // If no owner yet, just keep $record = null.
        if ($workspace?->owner_id) {
            $this->record = Company::findOrFail($workspace->owner_id);
        }
    }

    public function companyInfolist(Schema $infolist): Schema
    {
        return $infolist
            ->record($this->record)
            ->components([
                Section::make('Invoice Details')->schema([
                    TextEntry::make('name')->label('Company Name')->state(fn($record) => $record->name ?? "—"),
                    TextEntry::make('cui')->label('CUI')->state(fn($record) => $record->cui ?? "—"),
                    TextEntry::make('j')->label('J')->state(fn($record) => $record->j ?? "—"),
                    TextEntry::make('place_of_registration')->label('Place of Registration')->state(fn($record) => $record->place_of_registration ?? "—"),
                    TextEntry::make('iban')->label('IBAN')->state(fn($record) => $record->iban ?? "—"),
                    TextEntry::make('phone')->label('Phone')->state(fn($record) => $record->phone ?? "—"),
                    TextEntry::make('email')->label('Email')->state(fn($record) => $record->email ?? "—"),
                ])->columns(4),

                Section::make('Address')->schema([
                    TextEntry::make('address.street')->label('Street')->state(fn($record) => $record->address->street ?? "—"),
                    TextEntry::make('address.street_number')->label('No.')->state(fn($record) => $record->address->street_number ?? "—"),
                    TextEntry::make('address.building')->label('Building')->state(fn($record) => $record->address->building ?? "—"),
                    TextEntry::make('address.apartment_number')->label('Apt')->state(fn($record) => $record->address->apartment_number ?? "—"),
                    TextEntry::make('address.city')->label('City')->state(fn($record) => $record->address->city ?? "—"),
                    TextEntry::make('address.state')->label('State')->state(fn($record) => $record->address->state ?? "—"),
                    TextEntry::make('address.country')->label('Country')->state(fn($record) => $record->address->country ?? "—"),
                ])->columns(4),

                Section::make('Representative')
                    ->schema([
                    TextEntry::make('representative')
                        ->label('Name')
                        ->state(fn($record) => $record?->representative
                            ? $record->representative->getFilamentName()
                            : '—'),

                    TextEntry::make('representative.email')->label('Email')->state(fn($record) => $record->representative->email ?? "—")
                ])->columns(2),
            ])
            ->columns(1);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canInWorkspace('owner-company.view') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
