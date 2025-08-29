<?php

namespace App\Filament\Resources\CompanyEmployees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyEmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.first_name')->label('First Name'),
                TextColumn::make('user.last_name')->label('Last Name'),
                TextColumn::make('user.email')->label('Email'),
                TextColumn::make('job_title')->label('Title'),
                TextColumn::make('salary')->label('Salary'),
                TextColumn::make('hired_at')->label('Hired At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
