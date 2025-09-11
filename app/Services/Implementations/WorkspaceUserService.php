<?php

namespace App\Services\Implementations;

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
        $existingWorkspaceUser = WorkspaceUser::where('user_id', $user->id)
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
        return WorkspaceUser::where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->exists();
    }

    public function removeUserFromWorkspace(User $user, Workspace $workspace): bool
    {
        $deleted = WorkspaceUser::where('user_id', $user->id)
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
            WorkspaceUser::where('user_id', $user->id)
                ->update(['is_default' => false]);

            // Set the specified workspace as default
            WorkspaceUser::where('user_id', $user->id)
                ->where('workspace_id', $workspace->id)
                ->update(['is_default' => true]);
        });
    }

    private function shouldBeDefaultWorkspace(User $user): bool
    {
        // Set as default if user has no other workspaces
        return ! WorkspaceUser::where('user_id', $user->id)->exists();
    }
}
