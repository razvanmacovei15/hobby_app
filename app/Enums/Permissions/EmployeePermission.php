<?php

namespace App\Enums\Permissions;

enum EmployeePermission: string
{
    case VIEW = 'employees.view';
    case CREATE = 'employees.create';
    case EDIT = 'employees.edit';
    case DELETE = 'employees.delete';
}