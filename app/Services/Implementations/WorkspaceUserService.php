<?php

namespace App\Services\Implementations;

use App\Models\Permission\Role;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceUser;
use App\Services\IWorkspaceUserService;
use Illuminate\Support\Facades\DB;

class WorkspaceUserService implements IWorkspaceUserService
{
    public function addUserToWorkspace(User $user, Workspace $workspace, array $roleIds = []): WorkspaceUser
    {
        // Check if user is already in workspace
        $existingWorkspaceUser = WorkspaceUser::query()->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->first();

        if ($existingWorkspaceUser) {
            return $existingWorkspaceUser;
        }

        // Create workspace user relationship
        $workspaceUser = WorkspaceUser::create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'is_default' => $this->shouldBeDefaultWorkspace($user),
        ]);

        // Note: Role assignment is handled by the caller (e.g., InvitationService)
        // to ensure proper role objects are used instead of IDs

        return $workspaceUser;
    }

    public function isUserInWorkspace(User $user, Workspace $workspace): bool
    {
        return WorkspaceUser::query()->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->exists();
    }

    public function removeUserFromWorkspace(User $user, Workspace $workspace): bool
    {
        $deleted = WorkspaceUser::query()->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->delete();

        return $deleted > 0;
    }

    public function getUserWorkspaces(User $user)
    {
        return $user->workspaces()->with('workspaceUsers')->get();
    }

    public function setDefaultWorkspace(User $user, Workspace $workspace): void
    {
        DB::transaction(function () use ($user, $workspace) {
            // Remove default flag from all user's workspaces
            WorkspaceUser::query()->where('user_id', $user->id)
                ->update(['is_default' => false]);

            // Set the specified workspace as default
            WorkspaceUser::query()->where('user_id', $user->id)
                ->where('workspace_id', $workspace->id)
                ->update(['is_default' => true]);
        });
    }

    public function assignRolesToUser(WorkspaceUser $workspaceUser, array $roleIds): void
    {
        if (empty($roleIds)) {
            return;
        }

        $roles = Role::whereIn('id', $roleIds)
            ->where('workspace_id', $workspaceUser->workspace_id)
            ->get();

        foreach ($roles as $role) {
            $workspaceUser->user->assignRole($role);
        }
    }

    public function syncUserRoles(WorkspaceUser $workspaceUser, array $roleIds): void
    {
        $workspace = $workspaceUser->workspace;
        $user = $workspaceUser->user;

        // Get all existing workspace roles for this user
        $existingWorkspaceRoles = $user->roles()
            ->where('workspace_id', $workspace->id)
            ->get();

        // Remove all existing workspace roles
        foreach ($existingWorkspaceRoles as $role) {
            $user->removeRole($role);
        }

        // Assign new roles
        $this->assignRolesToUser($workspaceUser, $roleIds);
    }

    public function removeUserRoles(WorkspaceUser $workspaceUser, array $roleIds): void
    {
        if (empty($roleIds)) {
            return;
        }

        $roles = Role::whereIn('id', $roleIds)
            ->where('workspace_id', $workspaceUser->workspace_id)
            ->get();

        foreach ($roles as $role) {
            $workspaceUser->user->removeRole($role);
        }
    }

    public function mutateFormDataBeforeSave(array $data, ?WorkspaceUser $record): array
    {
        // Extract role IDs from form data
        $roleIds = $data['role_ids'] ?? [];

        // Remove role_ids from the data that goes to the model
        unset($data['role_ids']);
        // Handle role assignment after the record is saved
        if ($record) {
            // For editing: sync roles (remove old, assign new)
            $this->syncUserRoles($record, $roleIds);
        } else {
            // For creating: we need to handle this after record creation
            // Store role IDs temporarily (will be handled by afterCreate hook)
            session(['pending_workspace_user_roles' => $roleIds]);
        }

        return $data;
    }

    private function shouldBeDefaultWorkspace(User $user): bool
    {
        // Set as default if user has no other workspaces
        return ! WorkspaceUser::query()->where('user_id', $user->id)->exists();
    }
}
