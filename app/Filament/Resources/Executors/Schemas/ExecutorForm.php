<?php

namespace App\Filament\Resources\Executors\Schemas;

use App\Enums\ExecutorType;
use App\Models\Address;
use App\Models\Company;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class ExecutorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Executor company')
                    ->schema([
                        TextInput::make('executor.name')
                            ->label('Company Name')
                            ->required()
                            ->formatStateUsing(fn($record) => $record?->executor?->name),

                        TextInput::make('executor.cui')
                            ->label('CUI')
                            ->formatStateUsing(fn($record) => $record?->executor?->cui),

                        TextInput::make('executor.j')
                            ->label('J')
                            ->formatStateUsing(fn($record) => $record?->executor?->j),

                        TextInput::make('executor.place_of_registration')
                            ->label('Place of registration')
                            ->formatStateUsing(fn($record) => $record?->executor?->place_of_registration),

                        TextInput::make('executor.iban')
                            ->label('IBAN')
                            ->formatStateUsing(fn($record) => $record?->executor?->iban),

                        TextInput::make('executor.phone')
                            ->label('Phone')
                            ->tel()
                            ->formatStateUsing(fn($record) => $record?->executor?->phone),

                        TextInput::make('executor.email')
                            ->label('Email')
                            ->email()
                            ->formatStateUsing(fn($record) => $record?->executor?->email),
                    ])
                    ->columns(2),

                Section::make('Address')
                    ->schema([
                        TextInput::make('executor.address.street')
                            ->label('Street')
                            ->required()
                            ->maxLength(255)
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->street),

                        TextInput::make('executor.address.street_number')
                            ->label('No.')
                            ->required()
                            ->maxLength(50)
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->street_number),

                        TextInput::make('executor.address.building')
                            ->maxLength(50)
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->building),

                        TextInput::make('executor.address.apartment_number')
                            ->label('Apt')
                            ->maxLength(50)
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->apartment_number),

                        TextInput::make('executor.address.city')
                            ->maxLength(255)
                            ->required()
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->city),

                        TextInput::make('executor.address.state')
                            ->maxLength(255)
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->state),

                        TextInput::make('executor.address.country')
                            ->required()
                            ->maxLength(255)
                            ->formatStateUsing(fn($record) => $record?->executor?->address?->country),
                    ])
                    ->columns(4),

                Section::make('Representative')
                    ->schema([
                        TextInput::make('executor.representative.first_name')
                            ->label('First name')
                            ->required()
                            ->maxLength(100)
                            ->formatStateUsing(fn($record) => $record?->executor?->representative?->first_name),

                        TextInput::make('executor.representative.last_name')
                            ->label('Last name')
                            ->required()
                            ->maxLength(100)
                            ->formatStateUsing(fn($record) => $record?->executor?->representative?->last_name),

                        TextInput::make('executor.representative.email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(190)
                            ->helperText('If this email already exists, we’ll link that user as representative.')
                            ->formatStateUsing(fn($record) => $record?->executor?->representative?->email),
                    ])
                    ->columns(3),

                Section::make('Workspace details')->schema([
                    Select::make('executor_type')
                        ->label('Executor Type')
                        ->options(ExecutorType::options())
                        ->required(),
                    Checkbox::make('is_active')
                        ->label('Active'),
                ])->columns(2)
            ])
            ->columns(1);
    }
}
