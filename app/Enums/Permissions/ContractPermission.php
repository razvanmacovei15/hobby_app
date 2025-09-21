<?php

namespace App\Enums\Permissions;

enum ContractPermission: string
{
    case VIEW = 'contracts.view';
    case CREATE = 'contracts.create';
    case EDIT = 'contracts.edit';
    case DELETE = 'contracts.delete';
}