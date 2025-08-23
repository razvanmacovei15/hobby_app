<?php

namespace App\Filament\Resources\WorkReports\Pages;

use App\Filament\Resources\WorkReports\WorkReportResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkReport extends EditRecord
{
    protected static string $resource = WorkReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
