<?php

namespace App\Enums\Permissions;

enum WorkspaceExecutorPermission: string
{
    case VIEW = 'workspace-executors.view';
    case CREATE = 'workspace-executors.create';
    case EDIT = 'workspace-executors.edit';
    case DELETE = 'workspace-executors.delete';
    case OWN_VIEW = 'own-workspace-executors.view';
    case OWN_EDIT = 'own-workspace-executors.edit';
    case OWN_ASSIGN = 'own-workspace-executors.assign';
    case OWN_UNASSIGN = 'own-workspace-executors.unassign';
}
