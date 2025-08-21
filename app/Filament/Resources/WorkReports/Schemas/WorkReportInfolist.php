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
                TextEntry::make('contract.id')
                    ->numeric(),
                TextEntry::make('contractAnnex.id')
                    ->numeric(),
                TextEntry::make('written_by')
                    ->numeric(),
                TextEntry::make('report_month'),
                TextEntry::make('report_year')
                    ->numeric(),
                TextEntry::make('report_number')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('company.name')
                    ->numeric(),
            ]);
    }
}
