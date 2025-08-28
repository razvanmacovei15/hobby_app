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
            ],
            PermissionCategory::WORK_REPORTS->value => [
                'work-reports.view' => 'View work reports',
                'work-reports.create' => 'Create new work reports',
                'work-reports.edit' => 'Edit existing work reports',
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
