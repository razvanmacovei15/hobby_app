<?php

namespace App\Filament\Resources\BuildingPermits\Schemas;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BuildingPermitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('permit_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

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
            ]);
    }
}
