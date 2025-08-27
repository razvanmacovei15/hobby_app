<?php

namespace Database\Seeders;

use App\Models\Permission\Permission;
use App\Models\Permission\Role;
use App\Models\Workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkspacePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions for construction workspace management
        $permissions = [
            // Workspace Management
            'workspace.manage' => 'Manage workspace settings and users',
            'workspace.view' => 'View workspace information',
            
            // User Management
            'users.manage' => 'Add, edit, and remove users from workspace',
            'users.view' => 'View workspace users',
            'users.assign-roles' => 'Assign roles to workspace users',
            
            // Contract Management
            'contracts.create' => 'Create new contracts',
            'contracts.view' => 'View contracts',
            'contracts.edit' => 'Edit contracts',
            'contracts.delete' => 'Delete contracts',
            'contracts.approve' => 'Approve contracts',
            
            // Work Reports
            'work-reports.create' => 'Create work reports',
            'work-reports.view' => 'View work reports', 
            'work-reports.edit' => 'Edit work reports',
            'work-reports.delete' => 'Delete work reports',
            'work-reports.approve' => 'Approve work reports',
            'work-reports.view-all' => 'View all work reports (not just own)',
            
            // Building Permits
            'building-permits.view' => 'View building permits',
            'building-permits.manage' => 'Manage building permits',
            
            // Financial
            'financial.view' => 'View financial reports',
            'financial.manage' => 'Manage payments and invoices',
            
            // Construction Sites
            'sites.view' => 'View construction sites',
            'sites.manage' => 'Manage construction sites',
        ];

        // Define roles and their permissions
        $roles = [
            'workspace-admin' => [
                'name' => 'Workspace Administrator',
                'permissions' => array_keys($permissions), // All permissions
            ],
            'project-manager' => [
                'name' => 'Project Manager',
                'permissions' => [
                    'workspace.view', 'users.view', 'users.assign-roles',
                    'contracts.view', 'contracts.edit', 'contracts.approve',
                    'work-reports.create', 'work-reports.view', 'work-reports.edit', 'work-reports.approve', 'work-reports.view-all',
                    'building-permits.view', 'financial.view', 'sites.view', 'sites.manage'
                ],
            ],
            'site-supervisor' => [
                'name' => 'Site Supervisor',
                'permissions' => [
                    'workspace.view', 'users.view',
                    'contracts.view', 'work-reports.create', 'work-reports.view', 'work-reports.edit',
                    'building-permits.view', 'sites.view'
                ],
            ],
            'worker' => [
                'name' => 'Worker',
                'permissions' => [
                    'workspace.view', 'work-reports.create', 'work-reports.view',
                    'building-permits.view', 'sites.view'
                ],
            ],
            'financial-manager' => [
                'name' => 'Financial Manager', 
                'permissions' => [
                    'workspace.view', 'contracts.view', 'work-reports.view', 'work-reports.view-all',
                    'financial.view', 'financial.manage'
                ],
            ],
            'viewer' => [
                'name' => 'Viewer (Read Only)',
                'permissions' => [
                    'workspace.view', 'contracts.view', 'work-reports.view',
                    'building-permits.view', 'sites.view'
                ],
            ],
        ];

        // Get all workspaces
        $workspaces = Workspace::all();

        foreach ($workspaces as $workspace) {
            echo "Setting up permissions for workspace: {$workspace->name}\n";
            
            // Create permissions for this workspace
            foreach ($permissions as $permissionName => $description) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                    'workspace_id' => $workspace->id,
                ]);
            }

            // Create roles and assign permissions for this workspace
            foreach ($roles as $roleName => $roleData) {
                $role = Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web', 
                    'workspace_id' => $workspace->id,
                ]);

                // Assign permissions to role
                $rolePermissions = Permission::whereIn('name', $roleData['permissions'])
                    ->where('workspace_id', $workspace->id)
                    ->get();
                    
                $role->syncPermissions($rolePermissions);
                
                echo "  - Created role: {$roleData['name']} with " . count($roleData['permissions']) . " permissions\n";
            }
        }
        
        echo "Workspace permissions and roles setup complete!\n";
    }
}
