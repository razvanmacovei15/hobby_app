<?php

namespace App\Services;

use App\Models\User;
use App\Models\WorkspaceInvitation;

interface IWorkspaceInvitationService
{
    public function inviteEmployee(int $employeeId, int $workspaceId, array $roleIds): WorkspaceInvitation;

    public function sendInvitationEmail(WorkspaceInvitation $invitation): bool;

    public function acceptInvitation(string $token): array;

    public function acceptInvitationForExistingUser(string $token, User $user): array;

    public function getInvitationByToken(string $token): ?WorkspaceInvitation;
}
