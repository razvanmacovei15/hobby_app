<?php

namespace App\Filament\Resources\CompanyEmployees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyEmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->label('Employee Data')
                    ->components([
                        TextInput::make('user.first_name')->label('First Name'),
                        TextInput::make('user.last_name')->label('Last Name'),
                        TextInput::make('user.email')->label('Email'),
                    ]),

                Section::make('')->label('Job Info')->components([
                    TextInput::make('job_title')->label('Job Title'),
                    TextInput::make('salary')->label('Salary'),
                    DatePicker::make('hired_at')->label('Hired At')->native(false)->placeholder('Pick a date'),
                ])

            ])
            ->columns(2);
    }
}
