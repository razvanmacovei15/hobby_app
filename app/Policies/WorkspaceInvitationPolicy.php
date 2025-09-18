<?php

namespace App\Policies;

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
        return $user->canInWorkspace('workspace-invitations.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace('workspace-invitations.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canInWorkspace('workspace-invitations.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace('workspace-invitations.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace('workspace-invitations.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace('workspace-invitations.edit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkspaceInvitation $workspaceInvitation): bool
    {
        return $user->canInWorkspace('workspace-invitations.delete');
    }
}