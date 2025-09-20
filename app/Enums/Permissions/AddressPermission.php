<?php

namespace App\Enums\Permissions;

enum AddressPermission: string
{
    case VIEW = 'addresses.view';
    case CREATE = 'addresses.create';
    case EDIT = 'addresses.edit';
    case DELETE = 'addresses.delete';
}