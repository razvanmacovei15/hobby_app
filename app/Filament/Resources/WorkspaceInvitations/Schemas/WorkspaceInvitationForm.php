<?php

namespace App\Filament\Resources\WorkspaceInvitations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkspaceInvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('invitee.email')
                    ->label('Email')
                    ->disabled(),
                TextInput::make('invitee.first_name')
                    ->label('First Name')
                    ->disabled(),
                TextInput::make('invitee.last_name')
                    ->label('Last Name')
                    ->disabled(),
                TextInput::make('token')
                    ->label('Invitation Token')
                    ->disabled()
                    ->columnSpanFull(),
                DateTimePicker::make('expires_at')
                    ->label('Expires At')
                    ->disabled(),
                DateTimePicker::make('accepted_at')
                    ->label('Accepted At')
                    ->disabled(),
            ]);
    }
}
