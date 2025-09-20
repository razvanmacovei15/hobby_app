<?php

namespace App\Enums\Permissions;

enum OwnerCompanyPermission: string
{
    case VIEW = 'owner-company-page.view';
    case EDIT = 'owner-company-page.edit';
}
