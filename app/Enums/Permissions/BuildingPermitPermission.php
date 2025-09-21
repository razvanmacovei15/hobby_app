<?php

namespace App\Enums\Permissions;

enum BuildingPermitPermission: string
{
    case VIEW = 'building-permits.view';
    case CREATE = 'building-permits.create';
    case EDIT = 'building-permits.edit';
    case DELETE = 'building-permits.delete';
}