<?php

namespace App\Enums\Permissions;

enum WorkspaceInvitationPermission: string
{
    case VIEW = 'workspace-invitations.view';
    case CREATE = 'workspace-invitations.create';
    case EDIT = 'workspace-invitations.edit';
    case DELETE = 'workspace-invitations.delete';
}