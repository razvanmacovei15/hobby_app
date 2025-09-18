<?php

namespace App\Constants;

class PermissionConstants
{
    // Users permissions
    const USERS_VIEW = 'users.view';
    const USERS_CREATE = 'users.create';
    const USERS_EDIT = 'users.edit';
    const USERS_DELETE = 'users.delete';

    // Employees permissions
    const EMPLOYEES_VIEW = 'employees.view';
    const EMPLOYEES_CREATE = 'employees.create';
    const EMPLOYEES_EDIT = 'employees.edit';
    const EMPLOYEES_DELETE = 'employees.delete';

    // Work Reports permissions
    const WORK_REPORTS_VIEW = 'work-reports.view';
    const WORK_REPORTS_CREATE = 'work-reports.create';
    const WORK_REPORTS_EDIT = 'work-reports.edit';
    const WORK_REPORTS_DELETE = 'work-reports.delete';
    const WORK_REPORTS_APPROVE = 'work-reports.approve';

    // Workspace Executors permissions
    const WORKSPACE_EXECUTORS_VIEW = 'workspace-executors.view';
    const WORKSPACE_EXECUTORS_CREATE = 'workspace-executors.create';
    const WORKSPACE_EXECUTORS_EDIT = 'workspace-executors.edit';
    const WORKSPACE_EXECUTORS_DELETE = 'workspace-executors.delete';

    // Contracted Services permissions
    const CONTRACTED_SERVICES_VIEW = 'contracted-services.view';
    const CONTRACTED_SERVICES_CREATE = 'contracted-services.create';
    const CONTRACTED_SERVICES_EDIT = 'contracted-services.edit';
    const CONTRACTED_SERVICES_DELETE = 'contracted-services.delete';

    // Companies permissions
    const COMPANIES_VIEW = 'companies.view';
    const COMPANIES_CREATE = 'companies.create';
    const COMPANIES_EDIT = 'companies.edit';
    const COMPANIES_DELETE = 'companies.delete';

    // Contracts permissions
    const CONTRACTS_VIEW = 'contracts.view';
    const CONTRACTS_CREATE = 'contracts.create';
    const CONTRACTS_EDIT = 'contracts.edit';
    const CONTRACTS_DELETE = 'contracts.delete';

    // Contract Annexes permissions
    const CONTRACT_ANNEXES_VIEW = 'contract-annexes.view';
    const CONTRACT_ANNEXES_CREATE = 'contract-annexes.create';
    const CONTRACT_ANNEXES_EDIT = 'contract-annexes.edit';
    const CONTRACT_ANNEXES_DELETE = 'contract-annexes.delete';

    // Workspace permissions
    const WORKSPACE_VIEW = 'workspace.view';
    const WORKSPACE_EDIT = 'workspace.edit';
    const WORKSPACE_DELETE = 'workspace.delete';
    const WORKSPACE_MANAGE = 'workspace.manage';

    // Workspace Users permissions
    const WORKSPACE_USERS_VIEW = 'workspace-users.view';
    const WORKSPACE_USERS_CREATE = 'workspace-users.create';
    const WORKSPACE_USERS_EDIT = 'workspace-users.edit';
    const WORKSPACE_USERS_DELETE = 'workspace-users.delete';

    // Workspace Invitations permissions
    const WORKSPACE_INVITATIONS_VIEW = 'workspace-invitations.view';
    const WORKSPACE_INVITATIONS_CREATE = 'workspace-invitations.create';
    const WORKSPACE_INVITATIONS_EDIT = 'workspace-invitations.edit';
    const WORKSPACE_INVITATIONS_DELETE = 'workspace-invitations.delete';

    // Building Permits permissions
    const BUILDING_PERMITS_VIEW = 'building-permits.view';
    const BUILDING_PERMITS_CREATE = 'building-permits.create';
    const BUILDING_PERMITS_EDIT = 'building-permits.edit';
    const BUILDING_PERMITS_DELETE = 'building-permits.delete';

    // Addresses permissions
    const ADDRESSES_VIEW = 'addresses.view';
    const ADDRESSES_CREATE = 'addresses.create';
    const ADDRESSES_EDIT = 'addresses.edit';
    const ADDRESSES_DELETE = 'addresses.delete';

    // Roles permissions
    const ROLES_VIEW = 'roles.view';
    const ROLES_CREATE = 'roles.create';
    const ROLES_EDIT = 'roles.edit';
    const ROLES_DELETE = 'roles.delete';

    /**
     * Get all permissions grouped by category
     */
    public static function getAllPermissions(): array
    {
        return [
            'users' => [
                self::USERS_VIEW,
                self::USERS_CREATE,
                self::USERS_EDIT,
                self::USERS_DELETE,
            ],
            'employees' => [
                self::EMPLOYEES_VIEW,
                self::EMPLOYEES_CREATE,
                self::EMPLOYEES_EDIT,
                self::EMPLOYEES_DELETE,
            ],
            'work-reports' => [
                self::WORK_REPORTS_VIEW,
                self::WORK_REPORTS_CREATE,
                self::WORK_REPORTS_EDIT,
                self::WORK_REPORTS_DELETE,
                self::WORK_REPORTS_APPROVE,
            ],
            'workspace-executors' => [
                self::WORKSPACE_EXECUTORS_VIEW,
                self::WORKSPACE_EXECUTORS_CREATE,
                self::WORKSPACE_EXECUTORS_EDIT,
                self::WORKSPACE_EXECUTORS_DELETE,
            ],
            'contracted-services' => [
                self::CONTRACTED_SERVICES_VIEW,
                self::CONTRACTED_SERVICES_CREATE,
                self::CONTRACTED_SERVICES_EDIT,
                self::CONTRACTED_SERVICES_DELETE,
            ],
            'companies' => [
                self::COMPANIES_VIEW,
                self::COMPANIES_CREATE,
                self::COMPANIES_EDIT,
                self::COMPANIES_DELETE,
            ],
            'contracts' => [
                self::CONTRACTS_VIEW,
                self::CONTRACTS_CREATE,
                self::CONTRACTS_EDIT,
                self::CONTRACTS_DELETE,
            ],
            'contract-annexes' => [
                self::CONTRACT_ANNEXES_VIEW,
                self::CONTRACT_ANNEXES_CREATE,
                self::CONTRACT_ANNEXES_EDIT,
                self::CONTRACT_ANNEXES_DELETE,
            ],
            'workspace' => [
                self::WORKSPACE_VIEW,
                self::WORKSPACE_EDIT,
                self::WORKSPACE_DELETE,
                self::WORKSPACE_MANAGE,
            ],
            'workspace-users' => [
                self::WORKSPACE_USERS_VIEW,
                self::WORKSPACE_USERS_CREATE,
                self::WORKSPACE_USERS_EDIT,
                self::WORKSPACE_USERS_DELETE,
            ],
            'workspace-invitations' => [
                self::WORKSPACE_INVITATIONS_VIEW,
                self::WORKSPACE_INVITATIONS_CREATE,
                self::WORKSPACE_INVITATIONS_EDIT,
                self::WORKSPACE_INVITATIONS_DELETE,
            ],
            'building-permits' => [
                self::BUILDING_PERMITS_VIEW,
                self::BUILDING_PERMITS_CREATE,
                self::BUILDING_PERMITS_EDIT,
                self::BUILDING_PERMITS_DELETE,
            ],
            'addresses' => [
                self::ADDRESSES_VIEW,
                self::ADDRESSES_CREATE,
                self::ADDRESSES_EDIT,
                self::ADDRESSES_DELETE,
            ],
            'roles' => [
                self::ROLES_VIEW,
                self::ROLES_CREATE,
                self::ROLES_EDIT,
                self::ROLES_DELETE,
            ],
        ];
    }
}