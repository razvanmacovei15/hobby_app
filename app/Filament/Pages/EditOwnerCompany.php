<?php

namespace App\Filament\Pages;

use App\Enums\Permissions\OwnerCompanyPermission;
use App\Models\Company;
use App\Services\ICompanyService;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditOwnerCompany extends Page
{

    protected static string|null|\BackedEnum $navigationIcon = null;
    protected static ?string $navigationLabel = null;
    protected static ?string $title = 'Edit owner company';
    protected string $view = 'filament.pages.edit-owner-company';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public ?Company $record = null;

    public function mount(): void
    {
        $workspace = Filament::getTenant();

        if ($workspace?->owner_id) {
            // Editing existing
            $this->record = Company::findOrFail($workspace->owner_id);
            $this->form->fill($this->record->attributesToArray());
            static::$title = 'Edit owner company';
        } else {
            // Creating new
            $this->form->fill([]);
            static::$title = 'Create owner company';
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Executor company')
                    ->schema([
                        TextInput::make('name')
                            ->label('Company Name')
                            ->required()
                            ->formatStateUsing(fn ($record) => $record?->name),

                        TextInput::make('cui')
                            ->label('CUI')
                            ->required()
                            ->formatStateUsing(fn ($record) => $record?->cui),

                        TextInput::make('j')
                            ->label('J')
                            ->formatStateUsing(fn ($record) => $record?->j),

                        TextInput::make('place_of_registration')
                            ->label('Place of registration')
                            ->formatStateUsing(fn ($record) => $record?->place_of_registration),

                        TextInput::make('iban')
                            ->label('IBAN')
                            ->formatStateUsing(fn ($record) => $record?->iban),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->formatStateUsing(fn ($record) => $record?->phone),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->formatStateUsing(fn ($record) => $record?->email),
                    ])
                    ->columns(2),

                Section::make('Address')
                    ->schema([
                        TextInput::make('address.street')
                            ->label('Street')
                            ->required()
                            ->maxLength(255)
                            ->formatStateUsing(fn ($record) => $record?->address?->street),

                        TextInput::make('address.street_number')
                            ->label('No.')
                            ->maxLength(50)
                            ->formatStateUsing(fn ($record) => $record?->address?->street_number),

                        TextInput::make('address.building')
                            ->maxLength(50)
                            ->formatStateUsing(fn ($record) => $record?->address?->building),

                        TextInput::make('address.apartment_number')
                            ->label('Apt')
                            ->maxLength(50)
                            ->formatStateUsing(fn ($record) => $record?->address?->apartment_number),

                        TextInput::make('address.city')
                            ->required()
                            ->maxLength(255)
                            ->formatStateUsing(fn ($record) => $record?->address?->city),

                        TextInput::make('address.state')
                            ->maxLength(255)
                            ->formatStateUsing(fn ($record) => $record?->address?->state),

                        TextInput::make('address.country')
                            ->required()
                            ->maxLength(255)
                            ->formatStateUsing(fn ($record) => $record?->address?->country),
                    ])
                    ->columns(4),

                Section::make('Representative')
                    ->schema([
                        TextInput::make('representative.first_name')
                            ->label('First name')
                            ->required()
                            ->maxLength(100)
                            ->formatStateUsing(fn ($record) => $record?->representative?->first_name),

                        TextInput::make('representative.last_name')
                            ->label('Last name')
                            ->required()
                            ->maxLength(100)
                            ->formatStateUsing(fn ($record) => $record?->representative?->last_name),

                        TextInput::make('representative.email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(190)
                            ->helperText('If this email already exists, we’ll link that user as representative.')
                            ->formatStateUsing(fn ($record) => $record?->representative?->email),
                    ])
                    ->columns(3),
            ])
            ->record($this->record)
            ->statePath('data');
    }

    public function save(): void
    {
        $workspace = Filament::getTenant();

        $state = $this->form->getState();

        $companyData = collect($state)->except(['address', 'representative'])->toArray();
        $addressData = $state['address'] ?? [];
        $repData     = $state['representative'] ?? [];

        // If editing, include current company id so service updates instead of creating
        if ($this->record) {
            $companyData['id'] = $this->record->getKey();
        }

        $companyService = app(ICompanyService::class);

        $company = $companyService->createOrUpdateCompany($companyData, $addressData, $repData);

        // On first create, link workspace -> owner
        if (!$this->record && $workspace) {
            $workspace->update(['owner_id' => $company->getKey()]);
        }

        // Keep page state in sync
        $this->record = $company->fresh(['address', 'representative']);

        Notification::make()
            ->success()
            ->title('Owner company saved')
            ->send();

        // Back to read-only page
        $this->redirect(\App\Filament\Pages\OwnerCompany::getUrl());
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canInWorkspace(OwnerCompanyPermission::EDIT->value) ?? false;
    }
}
