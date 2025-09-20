<?php

namespace App\Enums\Permissions;

enum ContractedServicePermission: string
{
    case VIEW = 'contracted-services.view';
    case CREATE = 'contracted-services.create';
    case EDIT = 'contracted-services.edit';
    case DELETE = 'contracted-services.delete';
}