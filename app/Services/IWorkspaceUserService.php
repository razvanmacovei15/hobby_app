<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceUser;

interface IWorkspaceUserService
{
    public function addUserToWorkspace(User $user, Workspace $workspace, array $roleIds = []): WorkspaceUser;

    public function isUserInWorkspace(User $user, Workspace $workspace): bool;

    public function removeUserFromWorkspace(User $user, Workspace $workspace): bool;

    public function getUserWorkspaces(User $user);

    public function setDefaultWorkspace(User $user, Workspace $workspace): void;

    public function assignRolesToUser(WorkspaceUser $workspaceUser, array $roleIds): void;

    public function syncUserRoles(WorkspaceUser $workspaceUser, array $roleIds): void;

    public function removeUserRoles(WorkspaceUser $workspaceUser, array $roleIds): void;

    public function mutateFormDataBeforeSave(array $data, ?WorkspaceUser $record): array;
}
