<?php

namespace App\Services\Implementations;

use App\Enums\RoleTemplate;
use App\Models\Permission\Permission;
use App\Models\Permission\Role;
use App\Services\IRoleTemplateService;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleTemplateService implements IRoleTemplateService
{
    /**
     * Get all available role templates
     *
     * @return array<string, string>
     */
    public function getAvailableTemplates(): array
    {
        return RoleTemplate::options();
    }

    /**
     * Create a role from a template in the current workspace
     *
     * @param RoleTemplate $template
     * @param string|null $customName Optional custom name for the role
     * @return Role
     */
    public function createRoleFromTemplate(RoleTemplate $template, ?string $customName = null): Role
    {
        return DB::transaction(function () use ($template, $customName) {
            // Get current workspace
            $workspace = Filament::getTenant();
            if (!$workspace) {
                throw new \Exception('No workspace context available');
            }

            // Create role name and display name
            $roleName = $customName ?? $template->value;
            $displayName = $customName ?? $template->displayName();

            // Create the role
            $role = Role::create([
                'name' => $roleName,
                'display_name' => $displayName,
                'guard_name' => 'web',
                'workspace_id' => $workspace->id,
            ]);

            // Get permissions for this template
            $permissionNames = $template->getPermissions();
            
            if (!empty($permissionNames)) {
                // Find existing permissions
                $permissions = Permission::whereIn('name', $permissionNames)->get();
                
                // Log missing permissions for debugging
                $existingPermissionNames = $permissions->pluck('name')->toArray();
                $missingPermissions = array_diff($permissionNames, $existingPermissionNames);
                
                if (!empty($missingPermissions)) {
                    Log::warning('Some permissions not found when creating role from template', [
                        'template' => $template->value,
                        'missing_permissions' => $missingPermissions,
                        'role_id' => $role->id,
                    ]);
                }

                // Assign found permissions to role
                if ($permissions->isNotEmpty()) {
                    $role->givePermissionTo($permissions);
                    
                    Log::info('Role created from template', [
                        'template' => $template->value,
                        'role_id' => $role->id,
                        'role_name' => $roleName,
                        'permissions_assigned' => $permissions->count(),
                        'workspace_id' => $workspace->id,
                    ]);
                }
            }

            return $role;
        });
    }

    /**
     * Get permissions for a specific template
     *
     * @param RoleTemplate $template
     * @return array
     */
    public function getTemplatePermissions(RoleTemplate $template): array
    {
        return $template->getPermissions();
    }

    /**
     * Preview template permissions with descriptions
     *
     * @param RoleTemplate $template
     * @return array
     */
    public function previewTemplate(RoleTemplate $template): array
    {
        $permissionNames = $template->getPermissions();
        
        // Get permissions with descriptions from database
        $permissions = Permission::whereIn('name', $permissionNames)
            ->get()
            ->keyBy('name');

        $preview = [
            'template' => $template->displayName(),
            'description' => $template->description(),
            'permissions' => [],
            'permissions_count' => count($permissionNames),
        ];

        foreach ($permissionNames as $permissionName) {
            $permission = $permissions->get($permissionName);
            $preview['permissions'][] = [
                'name' => $permissionName,
                'description' => $permission?->description ?? 'Permission description not available',
                'category' => $permission?->category?->label() ?? 'Unknown',
                'exists' => $permission !== null,
            ];
        }

        return $preview;
    }

    /**
     * Check if template can be applied to current workspace
     *
     * @param RoleTemplate $template
     * @return bool
     */
    public function canApplyTemplate(RoleTemplate $template): bool
    {
        $workspace = Filament::getTenant();
        
        if (!$workspace) {
            return false;
        }

        // Check if required permissions exist in the system
        $requiredPermissions = $template->getPermissions();
        $existingPermissions = Permission::whereIn('name', $requiredPermissions)->count();
        
        // Template can be applied if at least some of its permissions exist
        return $existingPermissions > 0;
    }
}