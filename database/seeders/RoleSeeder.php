<?php

namespace Database\Seeders;

use App\Models\Permission\Permission;
use App\Models\Permission\Role;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Get first workspace for testing
        $workspace = Workspace::findOrFail(1);

        echo "Setting up roles for workspace: {$workspace->name}\n";

        // Create super-admin role
        $superAdminRole = Role::updateOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
            'workspace_id' => $workspace->id,
        ], [
            'display_name' => 'Super Administrator',
        ]);

        echo "  Created role: {$superAdminRole->display_name}\n";

        // Get all permissions for this workspace and assign them to super-admin
        $allPermissions = Permission::where('workspace_id', $workspace->id)->get();

        if ($allPermissions->isNotEmpty()) {
            $superAdminRole->syncPermissions($allPermissions);
            echo "  Assigned {$allPermissions->count()} permissions to super-admin role\n";

            foreach ($allPermissions as $permission) {
                echo "    - {$permission->name}\n";
            }
        } else {
            echo "  No permissions found for workspace {$workspace->id}\n";
        }

        // Assign super-admin role to user ID 3
        $user = User::findOrFail(3);
        if ($user) {
            $user->assignRole($superAdminRole);
            echo "  Assigned super-admin role to user: {$user->name} (ID: {$user->id})\n";
        } else {
            echo "  Warning: User with ID 3 not found\n";
        }

        echo "Role setup complete!\n";
    }
}
