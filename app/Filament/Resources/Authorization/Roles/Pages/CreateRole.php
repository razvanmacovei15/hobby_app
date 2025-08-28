<?php

namespace App\Filament\Resources\Authorization\Roles\Pages;

use App\Filament\Resources\Authorization\Roles\RoleResource;
use App\Services\IRoleService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $roleService = app(IRoleService::class);
        return $roleService->createRoleWithPermissions($data);
    }
}
