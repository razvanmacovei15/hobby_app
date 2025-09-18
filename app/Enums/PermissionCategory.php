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
    case CONTRACT_ANNEXES = 'contract-annexes';
    case WORKSPACE_USERS = 'workspace-users';
    case WORKSPACE_INVITATIONS = 'workspace-invitations';
    case BUILDING_PERMITS = 'building-permits';
    case ROLES = 'roles';

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
            self::CONTRACT_ANNEXES => 'Contract Annexes',
            self::WORKSPACE_USERS => 'Workspace Users',
            self::WORKSPACE_INVITATIONS => 'Workspace Invitations',
            self::BUILDING_PERMITS => 'Building Permits',
            self::ROLES => 'Roles',
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
            self::CONTRACT_ANNEXES => 'Contract annex management permissions',
            self::WORKSPACE_USERS => 'Workspace user management permissions',
            self::WORKSPACE_INVITATIONS => 'Workspace invitation management permissions',
            self::BUILDING_PERMITS => 'Building permit management permissions',
            self::ROLES => 'Role management permissions',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->all();
    }
}
