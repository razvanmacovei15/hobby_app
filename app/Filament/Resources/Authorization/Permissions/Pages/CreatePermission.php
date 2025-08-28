<?php

namespace App\Filament\Resources\Authorization\Permissions\Pages;

use App\Filament\Resources\Authorization\Permissions\PermissionResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the workspace_id to the current tenant
        $tenant = Filament::getTenant();
        if ($tenant) {
            $data['workspace_id'] = $tenant->id;
        }

        return $data;
    }
}
