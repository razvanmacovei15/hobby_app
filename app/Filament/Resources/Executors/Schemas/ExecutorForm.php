<?php

namespace App\Filament\Resources\Executors\Schemas;

use App\Models\Address;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                            ->formatStateUsing(fn ($record) => $record?->executor?->name),

                        TextInput::make('executor.cui')
                            ->label('CUI')
                            ->required()
                            ->formatStateUsing(fn ($record) => $record?->executor?->cui),

                        TextInput::make('executor.j')
                            ->label('J')
                            ->formatStateUsing(fn ($record) => $record?->executor?->j),

                        TextInput::make('executor.place_of_registration')
                            ->label('Place of registration')
                            ->formatStateUsing(fn ($record) => $record?->executor?->place_of_registration),

                        TextInput::make('executor.iban')
                            ->label('IBAN')
                            ->formatStateUsing(fn ($record) => $record?->executor?->iban),

                        Select::make('executor.representative_id')
                            ->label('Representative')
                            ->options(fn () => User::query()
                                ->selectRaw("id, CONCAT(first_name,' ', last_name) AS name")
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->formatStateUsing(fn ($record) => $record?->executor?->representative_id),

                        TextInput::make('executor.phone')
                            ->label('Phone')
                            ->tel()
                            ->formatStateUsing(fn ($record) => $record?->executor?->phone),
                    ])
                    ->columns(2),

                Section::make('Workspace link')->schema([
                    Toggle::make('is_active')->label('Active'),
                ]),

            ]);
    }

    public static function edit(): array
    {
        return [

        ];
    }
}
