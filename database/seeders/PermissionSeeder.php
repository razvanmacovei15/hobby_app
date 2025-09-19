<?php

namespace Database\Seeders;

use App\Enums\PermissionCategory;
use App\Models\Permission\Permission;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        echo "Setting up application-wide permissions:\n";

        // Define permissions organized by category
        $permissions = [
            PermissionCategory::USERS->value => [
                'users.view' => 'View users in the workspace',
                'users.create' => 'Create new users',
                'users.edit' => 'Edit user information and roles',
                'users.delete' => 'Delete users',
            ],
            PermissionCategory::EMPLOYEES->value => [
                'employees.view' => 'View employees list',
                'employees.create' => 'Create new employees',
                'employees.edit' => 'Edit employees information and roles',
                'employees.delete' => 'Delete employees',
            ],
            PermissionCategory::WORK_REPORTS->value => [
                'work-reports.view' => 'View work reports',
                'work-reports.create' => 'Create new work reports',
                'work-reports.edit' => 'Edit existing work reports',
                'work-reports.delete' => 'Delete existing work reports',
                'work-reports.approve' => 'Approve work reports',
            ],
            PermissionCategory::WORKSPACE_EXECUTORS->value => [
                'workspace-executors.view' => 'View workspace executors',
                'workspace-executors.create' => 'Create new workspace executors',
                'workspace-executors.edit' => 'Edit existing workspace executors',
                'workspace-executors.delete' => 'Delete existing workspace executors',
            ],
            PermissionCategory::CONTRACTED_SERVICES->value => [
                'contracted-services.view' => 'View contracted services',
                'contracted-services.create' => 'Create new contracted services',
                'contracted-services.edit' => 'Edit existing contracted services',
                'contracted-services.delete' => 'Delete existing contracted services',
            ],
            PermissionCategory::COMPANIES->value => [
                'companies.view' => 'View companies',
                'companies.create' => 'Create new companies',
                'companies.edit' => 'Edit existing companies',
                'companies.delete' => 'Delete existing companies',
            ],
            PermissionCategory::CONTRACTS->value => [
                'contracts.view' => 'View contracts',
                'contracts.create' => 'Create new contracts',
                'contracts.edit' => 'Edit existing contracts',
                'contracts.delete' => 'Delete existing contracts',
            ],
            PermissionCategory::CONTRACT_ANNEXES->value => [
                'contract-annexes.view' => 'View contract annexes',
                'contract-annexes.create' => 'Create new contract annexes',
                'contract-annexes.edit' => 'Edit existing contract annexes',
                'contract-annexes.delete' => 'Delete existing contract annexes',
            ],
            PermissionCategory::WORKSPACE->value => [
                'workspace.view' => 'View workspace details',
                'workspace.edit' => 'Edit workspace information',
                'workspace.delete' => 'Delete workspace',
                'workspace.manage' => 'Full workspace management',
            ],
            PermissionCategory::WORKSPACE_USERS->value => [
                'workspace-users.view' => 'View workspace users',
                'workspace-users.create' => 'Add users to workspace',
                'workspace-users.edit' => 'Edit workspace user roles',
                'workspace-users.delete' => 'Remove users from workspace',
            ],
            PermissionCategory::WORKSPACE_INVITATIONS->value => [
                'workspace-invitations.view' => 'View workspace invitations',
                'workspace-invitations.create' => 'Send workspace invitations',
                'workspace-invitations.edit' => 'Edit pending invitations',
                'workspace-invitations.delete' => 'Cancel workspace invitations',
            ],
            PermissionCategory::BUILDING_PERMITS->value => [
                'building-permits.view' => 'View building permits',
                'building-permits.create' => 'Create new building permits',
                'building-permits.edit' => 'Edit existing building permits',
                'building-permits.delete' => 'Delete existing building permits',
            ],
            PermissionCategory::ADDRESSES->value => [
                'addresses.view' => 'View addresses',
                'addresses.create' => 'Create new addresses',
                'addresses.edit' => 'Edit existing addresses',
                'addresses.delete' => 'Delete existing addresses',
            ],
            PermissionCategory::ROLES->value => [
                'roles.view' => 'View roles',
                'roles.create' => 'Create new roles',
                'roles.edit' => 'Edit existing roles',
                'roles.delete' => 'Delete existing roles',
            ],
            PermissionCategory::PERMISSIONS->value => [
                'permissions.view' => 'View permissions',
                'permissions.create' => 'Create new permissions',
                'permissions.edit' => 'Edit existing permissions',
                'permissions.delete' => 'Delete existing permissions',
            ]
        ];

        // Create permissions once (application-wide)
        foreach ($permissions as $category => $categoryPermissions) {
            echo "\nCreating {$category} permissions:\n";

            foreach ($categoryPermissions as $permissionName => $description) {
                Permission::updateOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ], [
                    'category' => $category,
                    'description' => $description,
                ]);
                echo "  - {$permissionName}: {$description}\n";
            }
        }

        echo "\nApplication-wide permissions setup complete!\n";
        echo "These permissions can now be assigned to roles in any workspace.\n";
    }
}
