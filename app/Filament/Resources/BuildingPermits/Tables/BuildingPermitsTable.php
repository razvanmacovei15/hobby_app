<?php

namespace App\Filament\Resources\BuildingPermits\Tables;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BuildingPermitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('display_name')
                    ->label('Permit Number')
                    ->getStateUsing(fn ($record) => $record->permit_number . '/' . $record->issuance_year)
                    ->searchable(['permit_number', 'issuance_year'])
                    ->sortable(['permit_number', 'issuance_year'])
                    ->weight('bold'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->name),

                TextColumn::make('permit_type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (PermitStatus $state) => match ($state) {
                        PermitStatus::PENDING => 'warning',
                        PermitStatus::APPROVED => 'success',
                        PermitStatus::REJECTED => 'danger',
                        PermitStatus::EXPIRED => 'gray',
                        PermitStatus::REVOKED => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('height_regime')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('architect')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('address.city')
                    ->label('Location')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('work_start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('work_end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('validity_term')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state && \Carbon\Carbon::parse($state)->isPast() ? 'danger' : 'success')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('workspace.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('buildings_count')
                    ->counts('buildings')
                    ->label('Buildings')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('permit_type')
                    ->options(PermitType::class),

                SelectFilter::make('status')
                    ->options(PermitStatus::class),

                Filter::make('height_regime')
                    ->form([
                        TextInput::make('height_regime')
                            ->label('Height Regime'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['height_regime'], fn ($q) => $q->where('height_regime', 'like', '%' . $data['height_regime'] . '%'));
                    }),

                Filter::make('work_start_date')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('From')
                            ->native()
                            ->placeholder('Filter start date from'),
                        DatePicker::make('end_date')
                            ->label('To')
                            ->native()
                            ->placeholder('Filter start date to'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn ($q) => $q->whereDate('work_start_date', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->whereDate('work_start_date', '<=', $data['end_date']));
                    }),

                Filter::make('validity_term')
                    ->form([
                        DatePicker::make('valid_from')
                            ->label('Valid From')
                            ->native()
                            ->placeholder('Filter validity from'),
                        DatePicker::make('valid_until')
                            ->label('Valid Until')
                            ->native()
                            ->placeholder('Filter validity until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['valid_from'], fn ($q) => $q->whereDate('validity_term', '>=', $data['valid_from']))
                            ->when($data['valid_until'], fn ($q) => $q->whereDate('validity_term', '<=', $data['valid_until']));
                    }),

                SelectFilter::make('workspace_id')
                    ->relationship('workspace', 'name'),

                Filter::make('architect')
                    ->form([
                        TextInput::make('architect')
                            ->label('Architect Name'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['architect'], fn ($q) => $q->where('architect', 'like', '%' . $data['architect'] . '%'));
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon(Heroicon::OutlinedEye),
                EditAction::make()
                    ->icon(Heroicon::OutlinedPencil),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
