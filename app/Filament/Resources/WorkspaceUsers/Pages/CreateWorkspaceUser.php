<?php

namespace App\Filament\Resources\WorkspaceUsers\Pages;

use App\Filament\Resources\WorkspaceUsers\WorkspaceUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkspaceUser extends CreateRecord
{
    protected static string $resource = WorkspaceUserResource::class;
}
