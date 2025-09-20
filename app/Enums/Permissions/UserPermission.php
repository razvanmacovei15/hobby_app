<?php

namespace App\Enums\Permissions;

enum UserPermission: string
{
    case VIEW = 'users.view';
    case CREATE = 'users.create';
    case EDIT = 'users.edit';
    case DELETE = 'users.delete';
}