<?php

namespace App\Filament\Resources\Executors\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
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
                    ->formatStateUsing(fn($record) => $record->executor?->representative?->getFilamentName())
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

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->sortable()
                    ->boolean(),

                Tables\Columns\IconColumn::make('has_contract')
                    ->label('Has Contract')
                    ->sortable()
                    ->boolean(),

            ])
            ->recordAction(ViewAction::class)
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
