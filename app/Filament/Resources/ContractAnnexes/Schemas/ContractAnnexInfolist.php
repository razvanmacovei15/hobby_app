<?php

namespace App\Filament\Resources\ContractAnnexes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContractAnnexInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sign_date')
                    ->date(),
            ]);
    }
}
