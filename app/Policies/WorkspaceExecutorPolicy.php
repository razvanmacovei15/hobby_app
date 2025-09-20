<?php

namespace App\Policies;

use App\Enums\Permissions\WorkspaceExecutorPermission;
use App\Models\User;
use App\Models\WorkspaceExecutor;
use Illuminate\Auth\Access\Response;

class WorkspaceExecutorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::DELETE->value);
    }
}
