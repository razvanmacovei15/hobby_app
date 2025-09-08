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
        // Get first workspace for testing
        $workspace = Workspace::findOrFail(1);

        echo "Setting up permissions for workspace: {$workspace->name}\n";

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
            ],
            PermissionCategory::WORKSPACE_EXECUTORS->value => [
                'workspace-executors.view' => 'View workspace executors',
                'workspace-executors.create' => 'Create new workspace executors',
                'workspace-executors.edit' => 'Edit existing workspace executors',
                'workspace-executors.delete' => 'Delete existing workspace executors',
            ]
        ];

        // Create permissions for this workspace
        foreach ($permissions as $category => $categoryPermissions) {
            echo "  Creating {$category} permissions:\n";

            foreach ($categoryPermissions as $permissionName => $description) {
                Permission::updateOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                    'workspace_id' => $workspace->id,
                ], [
                    'category' => $category,
                    'description' => $description,
                ]);
                echo "    - {$permissionName}: {$description}\n";
            }
        }

        echo "Authorization setup complete!\n";
    }
}
