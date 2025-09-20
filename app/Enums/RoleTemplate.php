<?php

namespace App\Enums;

use App\Enums\Permissions\UserPermission;
use App\Enums\Permissions\EmployeePermission;
use App\Enums\Permissions\WorkReportPermission;
use App\Enums\Permissions\WorkspaceExecutorPermission;
use App\Enums\Permissions\ContractedServicePermission;
use App\Enums\Permissions\CompanyPermission;
use App\Enums\Permissions\ContractPermission;
use App\Enums\Permissions\ContractAnnexPermission;
use App\Enums\Permissions\WorkspacePermission;
use App\Enums\Permissions\WorkspaceUserPermission;
use App\Enums\Permissions\WorkspaceInvitationPermission;
use App\Enums\Permissions\BuildingPermitPermission;
use App\Enums\Permissions\AddressPermission;
use App\Enums\Permissions\RolePermission;

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
                UserPermission::VIEW->value,
                UserPermission::CREATE->value,
                UserPermission::EDIT->value,
                UserPermission::DELETE->value,

                // Full permissions - Employees
                EmployeePermission::VIEW->value,
                EmployeePermission::CREATE->value,
                EmployeePermission::EDIT->value,
                EmployeePermission::DELETE->value,

                // Limited permissions - Work Reports (view + approve only)
                WorkReportPermission::VIEW->value,
                WorkReportPermission::APPROVE->value,

                // View only - Workspace Executors
                WorkspaceExecutorPermission::VIEW->value,

                // View only - Contracted Services
                ContractedServicePermission::VIEW->value,

                // View only - Companies
                CompanyPermission::VIEW->value,

                // Full permissions - Contracts
                ContractPermission::VIEW->value,
                ContractPermission::CREATE->value,
                ContractPermission::EDIT->value,
                ContractPermission::DELETE->value,

                // Full permissions - Contract Annexes
                ContractAnnexPermission::VIEW->value,
                ContractAnnexPermission::CREATE->value,
                ContractAnnexPermission::EDIT->value,
                ContractAnnexPermission::DELETE->value,

                // Full permissions - Workspace
                WorkspacePermission::VIEW->value,
                WorkspacePermission::EDIT->value,
                WorkspacePermission::DELETE->value,
                WorkspacePermission::MANAGE->value,

                // Full permissions - Workspace Users
                WorkspaceUserPermission::VIEW->value,
                WorkspaceUserPermission::CREATE->value,
                WorkspaceUserPermission::EDIT->value,
                WorkspaceUserPermission::DELETE->value,

                // Full permissions - Workspace Invitations
                WorkspaceInvitationPermission::VIEW->value,
                WorkspaceInvitationPermission::CREATE->value,
                WorkspaceInvitationPermission::EDIT->value,
                WorkspaceInvitationPermission::DELETE->value,

                // Full permissions - Building Permits
                BuildingPermitPermission::VIEW->value,
                BuildingPermitPermission::CREATE->value,
                BuildingPermitPermission::EDIT->value,
                BuildingPermitPermission::DELETE->value,

                // Full permissions - Addresses
                AddressPermission::VIEW->value,
                AddressPermission::CREATE->value,
                AddressPermission::EDIT->value,
                AddressPermission::DELETE->value,

                // Full permissions - Roles
                RolePermission::VIEW->value,
                RolePermission::CREATE->value,
                RolePermission::EDIT->value,
                RolePermission::DELETE->value,
            ],

            self::SITE_DIRECTOR => [
                // To be defined based on requirements
                WorkReportPermission::VIEW->value,
                WorkReportPermission::CREATE->value,
                WorkReportPermission::EDIT->value,
            ],

            self::ADMINISTRATOR_ENGINEER => [
                // To be defined based on requirements
                ContractPermission::VIEW->value,
                BuildingPermitPermission::VIEW->value,
            ],

            self::ORDERS_ENGINEER => [
                // To be defined based on requirements
                ContractedServicePermission::VIEW->value,
                ContractedServicePermission::CREATE->value,
            ],

            self::GENERAL_ENGINEER => [
                // To be defined based on requirements
                WorkReportPermission::VIEW->value,
                BuildingPermitPermission::VIEW->value,
            ],

            self::ADMIN => $this->getAllPermissions(),
        };
    }

    private function getAllPermissions(): array
    {
        return [
            // User permissions
            ...array_map(fn($case) => $case->value, UserPermission::cases()),
            // Employee permissions
            ...array_map(fn($case) => $case->value, EmployeePermission::cases()),
            // Work Report permissions
            ...array_map(fn($case) => $case->value, WorkReportPermission::cases()),
            // Workspace Executor permissions
            ...array_map(fn($case) => $case->value, WorkspaceExecutorPermission::cases()),
            // Contracted Service permissions
            ...array_map(fn($case) => $case->value, ContractedServicePermission::cases()),
            // Company permissions
            ...array_map(fn($case) => $case->value, CompanyPermission::cases()),
            // Contract permissions
            ...array_map(fn($case) => $case->value, ContractPermission::cases()),
            // Contract Annex permissions
            ...array_map(fn($case) => $case->value, ContractAnnexPermission::cases()),
            // Workspace permissions
            ...array_map(fn($case) => $case->value, WorkspacePermission::cases()),
            // Workspace User permissions
            ...array_map(fn($case) => $case->value, WorkspaceUserPermission::cases()),
            // Workspace Invitation permissions
            ...array_map(fn($case) => $case->value, WorkspaceInvitationPermission::cases()),
            // Building Permit permissions
            ...array_map(fn($case) => $case->value, BuildingPermitPermission::cases()),
            // Address permissions
            ...array_map(fn($case) => $case->value, AddressPermission::cases()),
            // Role permissions
            ...array_map(fn($case) => $case->value, RolePermission::cases()),
        ];
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($template) => [$template->value => $template->displayName()])
            ->all();
    }
}