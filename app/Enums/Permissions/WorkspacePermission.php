<?php

namespace App\Enums\Permissions;

enum WorkspacePermission: string
{
    case VIEW = 'workspace.view';
    case EDIT = 'workspace.edit';
    case DELETE = 'workspace.delete';
    case MANAGE = 'workspace.manage';
}