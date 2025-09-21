<?php

namespace App\Filament\Resources\WorkReports\Schemas;

use App\Enums\WorkReportStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WorkReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('contract.registration_key')
                    ->label('Contract')
                    ->numeric(),
                TextEntry::make('report_number')
                    ->label('Report Number'),

                TextEntry::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (WorkReportStatus $state): string => $state->label())
                    ->color(fn (WorkReportStatus $state): string => $state->color())
                    ->badge(),

                TextEntry::make('writtenBy.first_name')
                    ->label('Written By')
                    ->state(fn ($record) => $record?->writtenBy
                        ? $record->writtenBy->getFilamentName()
                        : '—'),

                TextEntry::make('approved_at')
                    ->label('Approved At')
                    ->dateTime()
                    ->placeholder('—'),

                TextEntry::make('approvedBy.first_name')
                    ->label('Approved By')
                    ->state(fn ($record) => $record?->approvedBy
                        ? $record->approvedBy->getFilamentName()
                        : '—'),

                TextEntry::make('notes')
                    ->label('Notes')
                    ->columnSpanFull()
                    ->placeholder('No notes provided'),

            ])
            ->columns(3);
    }
}
