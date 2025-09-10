<?php

namespace App\Enums;

enum PermissionCategory: string
{
    case USERS = 'users';
    case EMPLOYEES = 'employees';
    case WORK_REPORTS = 'work-reports';
    case CONTRACTS = 'contracts';
    case WORKSPACE = 'workspace';
    case WORKSPACE_EXECUTORS =  'workspace-executors';
    case CONTRACTED_SERVICES = 'contracted-services';
    case COMPANIES = 'companies';
    case ADDRESSES = 'addresses';

    public function label(): string
    {
        return match ($this) {
            self::USERS => 'Users',
            self::EMPLOYEES => 'Employees',
            self::WORK_REPORTS => 'Work Reports',
            self::CONTRACTS => 'Contracts',
            self::WORKSPACE => 'Workspace',
            self::WORKSPACE_EXECUTORS => 'Workspace-Executors',
            self::CONTRACTED_SERVICES => 'Contracted Services',
            self::COMPANIES => 'Companies',
            self::ADDRESSES => 'Addresses',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::USERS => 'User management permissions',
            self::EMPLOYEES => 'Employees permissions',
            self::WORK_REPORTS => 'Work report management permissions',
            self::CONTRACTS => 'Contract management permissions',
            self::WORKSPACE => 'Workspace management permissions',
            self::WORKSPACE_EXECUTORS => 'Workspace-Executors management permissions',
            self::CONTRACTED_SERVICES => 'Contracted services management permissions',
            self::COMPANIES => 'Company management permissions',
            self::ADDRESSES => 'Address management permissions',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->all();
    }
}
