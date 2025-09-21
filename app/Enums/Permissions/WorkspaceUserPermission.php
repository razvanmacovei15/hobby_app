<?php

namespace App\Enums\Permissions;

enum WorkspaceUserPermission: string
{
    case VIEW = 'workspace-users.view';
    case CREATE = 'workspace-users.create';
    case EDIT = 'workspace-users.edit';
    case DELETE = 'workspace-users.delete';
    case ASSIGN_ROLES = 'workspace-users.assign-roles';
}