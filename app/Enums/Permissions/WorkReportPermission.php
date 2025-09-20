<?php

namespace App\Enums\Permissions;

enum WorkReportPermission: string
{
    case VIEW = 'work-reports.view';
    case CREATE = 'work-reports.create';
    case EDIT = 'work-reports.edit';
    case DELETE = 'work-reports.delete';
    case APPROVE = 'work-reports.approve';
}