<?php

namespace App\Enums\Permissions;

enum BuildingPermitPagePermission: string
{
    case VIEW = 'building-permit-page.view';
    case EDIT = 'building-permit-page.edit';
}
