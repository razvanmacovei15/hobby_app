<?php

namespace App\Filament\Resources\Executors\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExecutorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('executor.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('executor.representative_id')
                    ->label('Representative')
                    ->formatStateUsing(fn ($record) => $record->executor?->representative?->getFilamentName())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Optional: search by name method parts
                        return $query->whereHas('executor.representative', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // Optional: sort by first_name or last_name
                        return $query->whereHas('executor.representative', function ($q) use ($direction) {
                            $q->orderBy('first_name', $direction);
                        });
                    }),

                Tables\Columns\TextColumn::make('executor.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('executor_type')->label('Service Type')->badge(),

                Tables\Columns\TextColumn::make('responsibleEngineer.first_name')
                    ->label('Responsible Engineer')
                    ->formatStateUsing(fn ($record) => $record->responsibleEngineer?->getFilamentName() ?? 'Not assigned')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('responsibleEngineer', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->leftJoin('users as responsible_engineer', 'workspace_executors.responsible_engineer_id', '=', 'responsible_engineer.id')
                            ->orderBy('responsible_engineer.first_name', $direction)
                            ->select('workspace_executors.*');
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->boolean(),

                Tables\Columns\IconColumn::make('has_contract')
                    ->label('Has Contract')
                    ->sortable()
                    ->boolean(),

            ])
            ->recordAction(ViewAction::class)
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Activity')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->default(true),
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make()
                    // optional: customize the icon/label/tooltip
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Edit')->color('edit'),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Remove executor from workspace')
                    ->modalDescription('This removes the executor link in this workspace. The Company record remains.')
                    ->modalSubmitActionLabel('Delete'),
            ]);
    }
}
