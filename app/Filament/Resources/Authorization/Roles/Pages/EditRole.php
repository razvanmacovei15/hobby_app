<?php

namespace App\Filament\Resources\Authorization\Roles\Pages;

use App\Filament\Resources\Authorization\Roles\RoleResource;
use App\Services\IRoleService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->icon('heroicon-o-trash'),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $roleService = app(IRoleService::class);
        return $roleService->updateRoleWithPermissions($record, $data);
    }
}
