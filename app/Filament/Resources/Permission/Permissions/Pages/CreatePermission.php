<?php

namespace App\Filament\Resources\Permission\Permissions\Pages;

use App\Filament\Resources\Permission\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
