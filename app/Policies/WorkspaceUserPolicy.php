<?php

namespace App\Policies;

use App\Enums\Permissions\WorkspaceUserPermission;
use App\Models\User;
use App\Models\WorkspaceUser;
use Illuminate\Auth\Access\Response;

class WorkspaceUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkspaceUser $workspaceUser): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkspaceUser $workspaceUser): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkspaceUser $workspaceUser): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkspaceUser $workspaceUser): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkspaceUser $workspaceUser): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::DELETE->value);
    }

    /**
     * Determine whether the user can assign roles to workspace users.
     */
    public function assignRoles(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceUserPermission::ASSIGN_ROLES->value);
    }
}