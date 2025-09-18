<?php

namespace App\Enums;

use App\Constants\PermissionConstants;

enum RoleTemplate: string
{
    case CEO = 'ceo';
    case SITE_DIRECTOR = 'site-director';
    case ADMINISTRATOR_ENGINEER = 'administrator-engineer';
    case ORDERS_ENGINEER = 'orders-engineer';
    case GENERAL_ENGINEER = 'general-engineer';
    case ADMIN = 'admin';

    public function displayName(): string
    {
        return match ($this) {
            self::CEO => 'CEO',
            self::SITE_DIRECTOR => 'Site Director',
            self::ADMINISTRATOR_ENGINEER => 'Administrator Engineer',
            self::ORDERS_ENGINEER => 'Orders Engineer',
            self::GENERAL_ENGINEER => 'General Engineer',
            self::ADMIN => 'Administrator',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::CEO => 'Chief Executive Officer with comprehensive oversight permissions',
            self::SITE_DIRECTOR => 'Site Director managing daily operations',
            self::ADMINISTRATOR_ENGINEER => 'Administrator Engineer handling technical administration',
            self::ORDERS_ENGINEER => 'Orders Engineer managing orders and procurement',
            self::GENERAL_ENGINEER => 'General Engineer with standard engineering permissions',
            self::ADMIN => 'System Administrator with all available permissions',
        };
    }

    public function getPermissions(): array
    {
        return match ($this) {
            self::CEO => [
                // Full permissions - Users
                PermissionConstants::USERS_VIEW,
                PermissionConstants::USERS_CREATE,
                PermissionConstants::USERS_EDIT,
                PermissionConstants::USERS_DELETE,

                // Full permissions - Employees
                PermissionConstants::EMPLOYEES_VIEW,
                PermissionConstants::EMPLOYEES_CREATE,
                PermissionConstants::EMPLOYEES_EDIT,
                PermissionConstants::EMPLOYEES_DELETE,

                // Limited permissions - Work Reports (view + approve only)
                PermissionConstants::WORK_REPORTS_VIEW,
                PermissionConstants::WORK_REPORTS_APPROVE,

                // View only - Workspace Executors
                PermissionConstants::WORKSPACE_EXECUTORS_VIEW,

                // View only - Contracted Services
                PermissionConstants::CONTRACTED_SERVICES_VIEW,

                // View only - Companies
                PermissionConstants::COMPANIES_VIEW,

                // Full permissions - Contracts
                PermissionConstants::CONTRACTS_VIEW,
                PermissionConstants::CONTRACTS_CREATE,
                PermissionConstants::CONTRACTS_EDIT,
                PermissionConstants::CONTRACTS_DELETE,

                // Full permissions - Contract Annexes
                PermissionConstants::CONTRACT_ANNEXES_VIEW,
                PermissionConstants::CONTRACT_ANNEXES_CREATE,
                PermissionConstants::CONTRACT_ANNEXES_EDIT,
                PermissionConstants::CONTRACT_ANNEXES_DELETE,

                // Full permissions - Workspace
                PermissionConstants::WORKSPACE_VIEW,
                PermissionConstants::WORKSPACE_EDIT,
                PermissionConstants::WORKSPACE_DELETE,
                PermissionConstants::WORKSPACE_MANAGE,

                // Full permissions - Workspace Users
                PermissionConstants::WORKSPACE_USERS_VIEW,
                PermissionConstants::WORKSPACE_USERS_CREATE,
                PermissionConstants::WORKSPACE_USERS_EDIT,
                PermissionConstants::WORKSPACE_USERS_DELETE,

                // Full permissions - Workspace Invitations
                PermissionConstants::WORKSPACE_INVITATIONS_VIEW,
                PermissionConstants::WORKSPACE_INVITATIONS_CREATE,
                PermissionConstants::WORKSPACE_INVITATIONS_EDIT,
                PermissionConstants::WORKSPACE_INVITATIONS_DELETE,

                // Full permissions - Building Permits
                PermissionConstants::BUILDING_PERMITS_VIEW,
                PermissionConstants::BUILDING_PERMITS_CREATE,
                PermissionConstants::BUILDING_PERMITS_EDIT,
                PermissionConstants::BUILDING_PERMITS_DELETE,

                // Full permissions - Addresses
                PermissionConstants::ADDRESSES_VIEW,
                PermissionConstants::ADDRESSES_CREATE,
                PermissionConstants::ADDRESSES_EDIT,
                PermissionConstants::ADDRESSES_DELETE,

                // Full permissions - Roles
                PermissionConstants::ROLES_VIEW,
                PermissionConstants::ROLES_CREATE,
                PermissionConstants::ROLES_EDIT,
                PermissionConstants::ROLES_DELETE,
            ],

            self::SITE_DIRECTOR => [
                // To be defined based on requirements
                PermissionConstants::WORK_REPORTS_VIEW,
                PermissionConstants::WORK_REPORTS_CREATE,
                PermissionConstants::WORK_REPORTS_EDIT,
            ],

            self::ADMINISTRATOR_ENGINEER => [
                // To be defined based on requirements
                PermissionConstants::CONTRACTS_VIEW,
                PermissionConstants::BUILDING_PERMITS_VIEW,
            ],

            self::ORDERS_ENGINEER => [
                // To be defined based on requirements
                PermissionConstants::CONTRACTED_SERVICES_VIEW,
                PermissionConstants::CONTRACTED_SERVICES_CREATE,
            ],

            self::GENERAL_ENGINEER => [
                // To be defined based on requirements
                PermissionConstants::WORK_REPORTS_VIEW,
                PermissionConstants::BUILDING_PERMITS_VIEW,
            ],

            self::ADMIN => $this->getAllPermissions(),
        };
    }

    private function getAllPermissions(): array
    {
        $allPermissions = [];
        foreach (PermissionConstants::getAllPermissions() as $categoryPermissions) {
            $allPermissions = array_merge($allPermissions, $categoryPermissions);
        }
        return $allPermissions;
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($template) => [$template->value => $template->displayName()])
            ->all();
    }
}