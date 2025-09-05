<?php

namespace App\Filament\Resources\WorkReports\Schemas;

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
                TextEntry::make('report_number'),

                TextEntry::make('writtenBy.first_name')
                    ->label('Written By')
                    ->state(fn ($record) => $record?->writtenBy
                        ? $record->writtenBy->getFilamentName()
                        : '—'),

            ])
            ->columns(3);
    }
}
