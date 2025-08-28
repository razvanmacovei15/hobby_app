<?php

namespace App\Services\Implementations;

use App\Models\Permission\Permission;
use App\Models\Permission\Role;
use App\Services\IRoleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService implements IRoleService
{
    /**
     * Create a new role with permissions
     *
     * @param array $data Role data including name, display_name and permission_ids_* arrays
     * @return Role
     */
    public function createRoleWithPermissions(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            // Extract permission IDs from the categorized data
            $permissionIds = $this->extractPermissionIds($data);

            // Create the role with basic data (name, display_name)
            $roleData = [
                'name' => $data['name'],
                'display_name' => $data['display_name'] ?? null,
            ];

            $role = Role::create($roleData);

            // Assign permissions if any were selected
            if (!empty($permissionIds)) {
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->givePermissionTo($permissions);
            }

            return $role;
        });
    }

    /**
     * Update a role with permissions
     *
     * @param Role $role
     * @param array $data Role data including name, display_name and permission_ids_* arrays
     * @return Role
     */
    public function updateRoleWithPermissions(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            // Extract permission IDs from the categorized data
            $permissionIds = $this->extractPermissionIds($data);

            // Update the role with basic data (name, display_name)
            $role->update([
                'name' => $data['name'],
                'display_name' => $data['display_name'] ?? null,
            ]);

            // Sync permissions - this will remove old permissions and add new ones
            if (!empty($permissionIds)) {
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
            } else {
                // Remove all permissions if none were selected
                $role->syncPermissions([]);

                Log::info('All permissions removed from role', [
                    'role_id' => $role->id,
                    'role_name' => $role->name
                ]);
            }

            return $role;
        });
    }

    /**
     * Extract permission IDs from categorized permission data
     *
     * @param array $data
     * @return array
     */
    public function extractPermissionIds(array $data): array
    {
        $permissionIds = [];

        // Look for keys that start with 'permission_ids_'
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'permission_ids_') && is_array($value)) {
                // Convert string IDs to integers and merge with existing IDs
                $categoryPermissionIds = array_map('intval', array_filter($value));
                $permissionIds = array_merge($permissionIds, $categoryPermissionIds);
            }
        }

        // Remove duplicates and return
        return array_unique($permissionIds);
    }
}
