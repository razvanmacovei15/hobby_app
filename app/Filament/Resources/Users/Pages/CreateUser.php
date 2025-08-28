<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $workspace = Filament::getTenant();
        $user = User::find($data['user_id']);
        
        if (!$user || !$workspace) {
            throw new \Exception('User or workspace not found');
        }

        // Add user to workspace if not already added
        if (!$user->workspaces()->where('workspace_id', $workspace->id)->exists()) {
            $user->workspaces()->attach($workspace->id);
        }

        // Assign roles if provided
        if (isset($data['roles']) && is_array($data['roles'])) {
            foreach ($data['roles'] as $roleId) {
                $role = \App\Models\Permission\Role::find($roleId);
                if ($role && $role->workspace_id === $workspace->id) {
                    $user->assignRole($role);
                }
            }
        }

        return $user;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
