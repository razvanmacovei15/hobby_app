<?php

namespace App\Enums\Permissions;

enum RolePermission: string
{
    case VIEW = 'roles.view';
    case CREATE = 'roles.create';
    case EDIT = 'roles.edit';
    case DELETE = 'roles.delete';
}