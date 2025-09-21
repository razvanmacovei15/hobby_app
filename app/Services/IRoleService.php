<?php

namespace App\Services;

use App\Models\Permission\Role;

interface IRoleService
{
    /**
     * Create a new role with permissions
     *
     * @param array $data Role data including name, display_name and permission_ids_* arrays
     * @return Role
     */
    public function createRoleWithPermissions(array $data): Role;

    /**
     * Update a role with permissions
     *
     * @param Role $role
     * @param array $data Role data including name, display_name and permission_ids_* arrays
     * @return Role
     */
    public function updateRoleWithPermissions(Role $role, array $data): Role;

    /**
     * Extract permission IDs from categorized permission data
     *
     * @param array $data
     * @return array
     */
    public function extractPermissionIds(array $data): array;
    public function getCurrentWorkspaceRoles();
}
