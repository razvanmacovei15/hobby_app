<?php

namespace App\Filament\Pages;

use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EditOwnerCompany extends Page
{

    protected static string|null|\BackedEnum $navigationIcon = null; // hide from sidebar
    protected static ?string $navigationLabel = null;
    protected static ?string $title = 'Edit owner company';
    protected string $view = 'filament.pages.edit-owner-company';
    protected static bool $shouldRegisterNavigation = false; // ✅ hide from sidebar

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
        $addressData = $state['address'] ?? null;
        $repData     = $state['representative'] ?? null;

        DB::transaction(function () use ($workspace, $companyData, $addressData, $repData) {
            // 1) CREATE or UPDATE the company core
            if ($this->record) {
                $company = $this->record->fill($companyData);
            } else {
                $company = new Company($companyData);
            }

            // 2) Address: upsert separately (no ->relationship())
            if ($addressData && array_filter($addressData, fn ($v) => $v !== null && $v !== '')) {
                if ($company->address) {
                    $company->address->fill($addressData)->save();
                } else {
                    $address = Address::create($addressData);
                    $company->address()->associate($address);
                }
            }

            // 3) Representative: create-or-update by email, then associate
            // --- representative upsert + associate ---
            if (! empty($repData)) {
                // Prefer to resolve by email to avoid duplicates
                $resolved = null;

                if (! empty($repData['email'])) {
                    $resolved = User::query()->firstWhere('email', $repData['email']);
                }

                if ($resolved) {
                    // Update name fields on the existing user (don’t touch password)
                    $resolved->fill(Arr::only($repData, ['first_name', 'last_name']))->save();
                    $user = $resolved;
                } else {
                    // Create a new user with a random password (hashed by cast)
                    $user = new User();
                    $user->fill(Arr::only($repData, ['first_name', 'last_name', 'email']));
                    $user->save();
                    // Associate on the company
                    $company->representative()->associate($user);
                }
            }


            // 4) Persist company (will also persist address association & representative_id)
            $company->save();

            // 5) On first create, link workspace -> owner
            if (!$this->record && $workspace) {
                $workspace->update(['owner_id' => $company->getKey()]);
            }

            // Keep page state in sync
            $this->record = $company->fresh(['address', 'representative']);
        });

        Notification::make()
            ->success()
            ->title('Owner company saved')
            ->send();

        // Back to read-only page
        $this->redirect(\App\Filament\Pages\OwnerCompany::getUrl());
    }

}
