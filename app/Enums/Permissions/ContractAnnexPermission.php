<?php

namespace App\Enums\Permissions;

enum ContractAnnexPermission: string
{
    case VIEW = 'contract-annexes.view';
    case CREATE = 'contract-annexes.create';
    case EDIT = 'contract-annexes.edit';
    case DELETE = 'contract-annexes.delete';
}