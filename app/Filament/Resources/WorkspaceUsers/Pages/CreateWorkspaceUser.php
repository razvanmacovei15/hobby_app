<?php

namespace App\Filament\Resources\WorkspaceUsers\Pages;

use App\Filament\Resources\WorkspaceUsers\WorkspaceUserResource;
use App\Services\IWorkspaceUserService;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkspaceUser extends CreateRecord
{
    protected static string $resource = WorkspaceUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var IWorkspaceUserService $svc */
        $svc = app(IWorkspaceUserService::class);
        return $svc->mutateFormDataBeforeSave($data, null);
    }
}
