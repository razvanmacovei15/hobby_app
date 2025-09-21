<?php

namespace App\Policies;

use App\Enums\Permissions\WorkspaceExecutorPermission;
use App\Models\User;
use App\Models\WorkspaceExecutor;

class WorkspaceExecutorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceExecutorPermission::VIEW->value) ||
               $user->canInWorkspace(WorkspaceExecutorPermission::OWN_VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        if ($user->canInWorkspace(WorkspaceExecutorPermission::VIEW->value)) {
            return true;
        }

        if ($user->canInWorkspace(WorkspaceExecutorPermission::OWN_VIEW->value)) {
            return $this->isAssignedEngineer($user, $workspaceExecutor);
        }

        return false;
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
        if ($user->canInWorkspace(WorkspaceExecutorPermission::EDIT->value)) {
            return true;
        }

        if ($user->canInWorkspace(WorkspaceExecutorPermission::OWN_EDIT->value)) {
            return $this->isAssignedEngineer($user, $workspaceExecutor);
        }

        return false;
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

    /**
     * Determine whether the user can assign engineers to the executor.
     */
    public function assignEngineer(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        if ($user->canInWorkspace(WorkspaceExecutorPermission::EDIT->value)) {
            return true;
        }

        if ($user->canInWorkspace(WorkspaceExecutorPermission::OWN_ASSIGN->value)) {
            return $this->isAssignedEngineer($user, $workspaceExecutor);
        }

        return false;
    }

    /**
     * Determine whether the user can unassign engineers from the executor.
     */
    public function unassignEngineer(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        if ($user->canInWorkspace(WorkspaceExecutorPermission::EDIT->value)) {
            return true;
        }

        if ($user->canInWorkspace(WorkspaceExecutorPermission::OWN_UNASSIGN->value)) {
            return $this->isAssignedEngineer($user, $workspaceExecutor);
        }

        return false;
    }

    /**
     * Check if the user is assigned as an engineer to the workspace executor.
     */
    private function isAssignedEngineer(User $user, WorkspaceExecutor $workspaceExecutor): bool
    {
        return $workspaceExecutor->engineers()->where('user_id', $user->id)->exists();
    }
}
