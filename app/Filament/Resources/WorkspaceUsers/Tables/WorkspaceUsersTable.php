<?php

namespace App\Filament\Resources\WorkspaceUsers\Tables;

use Filament\Actions\Action;
use App\Services\IWorkspaceUserService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class WorkspaceUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.id')
                    ->label('Full Name')
                    ->formatStateUsing(fn($record) => $record->user->getFilamentName())
                    ->searchable(['user.first_name', 'user.last_name'])
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(['user.email'])
                    ->sortable(),

                TextColumn::make('roles')
                    ->label('Roles')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $currentWorkspace = Filament::getTenant();
                        $roles = $record->user->getWorkspaceRoles($currentWorkspace);
                        return $roles->pluck('display_name')->toArray();
                    })
                    ->listWithLineBreaks(),

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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->using(function (array $data, $record) {
                        /** @var IWorkspaceUserService $svc */
                        $svc = app(IWorkspaceUserService::class);
                        
                        // Process the form data (handles role assignment internally)
                        $processedData = $svc->mutateFormDataBeforeSave($data, $record);
                        
                        // Update the record with the processed data
                        $record->update($processedData);
                        
                        return $record;
                    }),
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
