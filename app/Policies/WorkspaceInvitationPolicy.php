<?php

namespace App\Policies;

use App\Enums\Permissions\WorkspaceInvitationPermission;
use App\Models\User;
use App\Models\WorkspaceInvitation;
use Illuminate\Auth\Access\Response;

class WorkspaceInvitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::VIEW->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::VIEW->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::EDIT->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::DELETE->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::EDIT->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace(WorkspaceInvitationPermission::DELETE->value);
    }
}