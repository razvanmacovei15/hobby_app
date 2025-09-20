<?php

namespace App\Enums\Permissions;

enum CompanyPermission: string
{
    case VIEW = 'companies.view';
    case CREATE = 'companies.create';
    case EDIT = 'companies.edit';
    case DELETE = 'companies.delete';
}