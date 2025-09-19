<?php

namespace App\Filament\Resources\WorkspaceUsers\Pages;

use App\Filament\Resources\WorkspaceUsers\WorkspaceUserResource;
use App\Services\IWorkspaceUserService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkspaceUser extends EditRecord
{
    protected static string $resource = WorkspaceUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var IWorkspaceUserService $svc */
        $svc = app(IWorkspaceUserService::class);
        return $svc->mutateFormDataBeforeSave($data, $this->record);
    }
}
