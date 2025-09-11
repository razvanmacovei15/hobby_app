<?php

namespace App\Filament\Resources\WorkReports\Tables;

use App\Enums\WorkReportStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class WorkReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('executor.name')
                    ->label('Executor Company')
                    ->sortable(),
                TextColumn::make('report_month')
                    ->searchable()
                    ->formatStateUsing(fn($state) => Carbon::create()->locale(app()->getLocale())->month((int)$state)->isoFormat('MMMM')
                    ),
                TextColumn::make('report_year')
                    ->sortable(),
                TextColumn::make('report_number')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('writtenBy.first_name')
                    ->label('Written By')
                    ->state(fn($record) => $record?->writtenBy
                        ? $record->writtenBy->getFilamentName()
                        : '—')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(WorkReportStatus $state): string => $state->label())
                    ->color(fn(WorkReportStatus $state): string => $state->color())
                    ->badge()
                    ->sortable()
                    ->summarize([
                        Count::make()
                    ]),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('report_month')
                    ->label('Month')
                    ->options([
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ])
                    ->default(now()->month),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        WorkReportStatus::DRAFT->value => WorkReportStatus::DRAFT->label(),
                        WorkReportStatus::PENDING_APPROVAL->value => WorkReportStatus::PENDING_APPROVAL->label(),
                        WorkReportStatus::APPROVED->value => WorkReportStatus::APPROVED->label(),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->color('edit'),
                DeleteAction::make()->color('delete'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
