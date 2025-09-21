<?php

namespace App\Filament\Resources\CompanyEmployees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
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
                TextColumn::make('job_title')->label('Title')->placeholder('—'),
                TextColumn::make('salary')->label('Salary')->placeholder('—'),
                TextColumn::make('hired_at')->label('Hired At')->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->icon('heroicon-o-pencil'),
                DeleteAction::make()->icon('heroicon-o-trash'),
                ForceDeleteAction::make()->icon('heroicon-o-trash'),
                RestoreAction::make()->icon('heroicon-o-arrow-path'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
