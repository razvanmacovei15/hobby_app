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
        // Get all workspaces
        $workspaces = Workspace::all();

        if ($workspaces->isEmpty()) {
            echo "No workspaces found. Please create workspaces first.\n";
            return;
        }

        echo "Setting up roles for " . $workspaces->count() . " workspace(s):\n";

        // Create roles for each workspace
        foreach ($workspaces as $workspace) {
            echo "\nSetting up roles for workspace: {$workspace->name} (ID: {$workspace->id})\n";

            // Create super-admin role
            $superAdminRole = Role::updateOrCreate([
                'name' => 'super-admin',
                'guard_name' => 'web',
                'workspace_id' => $workspace->id,
            ], [
                'display_name' => 'Super Administrator',
            ]);

            echo "  Created role: {$superAdminRole->display_name}\n";

            // Get all permissions (application-wide) and assign them to super-admin
            $allPermissions = Permission::all();

            if ($allPermissions->isNotEmpty()) {
                $superAdminRole->syncPermissions($allPermissions);
                echo "  Assigned {$allPermissions->count()} permissions to super-admin role\n";

                foreach ($allPermissions as $permission) {
                    echo "    - {$permission->name}\n";
                }
            } else {
                echo "  No permissions found for workspace {$workspace->id}\n";
            }

            // Try to assign super-admin role to first user associated with this workspace
            $user = $workspace->users()->first();
            if ($user) {
                $user->assignRole($superAdminRole);
                echo "  Assigned super-admin role to user: " . $user->getFilamentName() . " (ID: " . $user->id . ")\n";
            } else {
                echo "  Warning: No users found in workspace " . $workspace->id . "\n";
            }

            // Also assign super-admin role to user 3 (razvanmc15@gmail.com) if they exist and have workspace access
            $user3 = User::findOrFail(3);
            if ($user3 && $workspace->users()->where('users.id', 3)->exists()) {
                // Check if user already has this role to avoid duplicates
                if (!$user3->hasRole($superAdminRole)) {
                    $user3->assignRole($superAdminRole);
                    echo "  Also assigned super-admin role to user: " . $user3->getFilamentName() . " (ID: " . $user3->id . ")\n";
                } else {
                    echo "  User " . $user3->getFilamentName() . " already has super-admin role in this workspace\n";
                }
            }
        }

        echo "\nRole setup complete for all workspaces!\n";
    }
}
