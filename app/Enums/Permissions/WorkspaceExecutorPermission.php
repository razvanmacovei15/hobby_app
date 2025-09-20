<?php

namespace App\Enums\Permissions;

enum WorkspaceExecutorPermission: string
{
    case VIEW = 'workspace-executors.view';
    case CREATE = 'workspace-executors.create';
    case EDIT = 'workspace-executors.edit';
    case DELETE = 'workspace-executors.delete';
}