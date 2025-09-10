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
            PermissionCategory::ADDRESSES->value => [
                'addresses.view' => 'View addresses',
                'addresses.create' => 'Create new addresses',
                'addresses.edit' => 'Edit existing addresses',
                'addresses.delete' => 'Delete existing addresses',
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
