<?php

namespace App\Enums\Permissions;

enum PermissionPermission: string
{
    case VIEW = 'permissions.view';
    case CREATE = 'permissions.create';
    case EDIT = 'permissions.edit';
    case DELETE = 'permissions.delete';
}