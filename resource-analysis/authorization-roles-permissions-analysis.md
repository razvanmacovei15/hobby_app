# Authorization (Roles & Permissions) Resources - Analysis & Improvement Recommendations

## **Current Implementation Overview**

The authorization system implements a sophisticated workspace-scoped role and permission management system using Spatie Laravel Permission package, providing granular access control for multi-tenant construction project management.

### **Files Structure**
```
app/Filament/Resources/Authorization/
├── Roles/
│   ├── RoleResource.php                # Workspace-scoped role management
│   ├── Pages/
│   │   ├── CreateRole.php             # Role creation with service integration
│   │   ├── EditRole.php               # Role editing with permission sync
│   │   └── ListRoles.php              # Role listing
│   ├── Schemas/
│   │   └── RoleForm.php               # Dynamic form with categorized permissions
│   └── Tables/
│       └── RolesTable.php             # Role table with permission/user counts
└── Permissions/
    ├── PermissionResource.php         # Workspace-scoped permission management
    ├── Pages/
    │   ├── CreatePermission.php       # Permission creation with workspace assignment
    │   ├── EditPermission.php         # Permission editing
    │   └── ListPermissions.php        # Permission listing
    ├── Schemas/
    │   └── PermissionForm.php          # Permission form with category selection
    └── Tables/
        └── PermissionTable.php        # Grouped permission table

app/Models/Permission/
├── Role.php                           # Extended Spatie Role with workspace scoping
└── Permission.php                     # Extended Spatie Permission with categories

app/Services/
├── IRoleService.php                   # Role service interface
└── Implementations/RoleService.php    # Role creation with permission assignment

app/Enums/
└── PermissionCategory.php            # Permission categorization enum
```

## **Business Logic Analysis**

### **Strengths ✅**

**Advanced Permission Architecture:**
- **Workspace Scoping**: Perfect implementation of workspace-isolated permissions and roles
- **Spatie Integration**: Proper extension of Spatie Laravel Permission with workspace awareness
- **Dynamic Permission Form**: Intelligent form that dynamically generates sections based on available permission categories
- **Category Organization**: Clean permission categorization with descriptions and labels
- **Service Layer Integration**: Proper service-based role creation with transaction safety

**Technical Excellence:**
- **Race Condition Safety**: Uses database transactions for role/permission assignment
- **Proper Scoping**: EloquentQuery properly filters by workspace tenant
- **Auto-Assignment**: Automatic workspace_id assignment during creation
- **Permission Extraction**: Smart extraction of categorized permission IDs from form data
- **Validation**: Proper uniqueness validation scoped to workspace

**User Experience:**
- **Intuitive Interface**: Color-coded role badges with semantic meaning
- **Permission Grouping**: Permissions grouped by category for better organization
- **Real-time Counts**: Live counts of permissions and users per role
- **Navigation Organization**: Proper grouping in "Authorization" section

### **Critical Issues ⚠️**

**Limited Permission Categories:**
- **Basic Categories Only**: Only 4 categories (Users, Work Reports, Contracts, Workspace) vs. comprehensive construction needs
- **Missing Construction-Specific Permissions**: No quality control, safety, financial, or field operations permissions
- **No Hierarchical Permissions**: Missing resource-specific permission inheritance

**Missing Role Templates:**
- **Manual Role Creation**: No predefined role templates for construction industry standards
- **Limited Seeder Data**: Basic seeder with minimal real-world construction roles
- **No Role Inheritance**: Missing role hierarchy (e.g., Site Supervisor extends Field Worker permissions)

**User Experience Gaps:**
- **No Role Preview**: Missing role capability preview before assignment
- **Basic Permission Display**: No visual permission matrix or capability overview
- **Missing Role Analytics**: No usage tracking or role effectiveness metrics
- **No Bulk Operations**: Limited bulk role/permission management

## **Improvement Recommendations**

### **1. Comprehensive Construction Permission Categories (High Priority)**

**Expand PermissionCategory Enum:**
```php
enum PermissionCategory: string {
    // Core Management
    case WORKSPACE = 'workspace';
    case USERS = 'users';
    case COMPANIES = 'companies';
    
    // Project Management
    case CONTRACTS = 'contracts';
    case PROJECT_MANAGEMENT = 'project_management';
    case CONSTRUCTION_SITES = 'construction_sites';
    case BUILDING_PERMITS = 'building_permits';
    
    // Work Execution
    case WORK_REPORTS = 'work_reports';
    case EXECUTORS = 'executors';
    case SERVICES = 'services';
    case PROGRESS_TRACKING = 'progress_tracking';
    
    // Quality & Safety
    case QUALITY_CONTROL = 'quality_control';
    case SAFETY_MANAGEMENT = 'safety_management';
    case INSPECTIONS = 'inspections';
    case COMPLIANCE = 'compliance';
    
    // Financial Management
    case FINANCIAL = 'financial';
    case PAYMENTS = 'payments';
    case INVOICING = 'invoicing';
    case BUDGET_MANAGEMENT = 'budget_management';
    
    // Document Management
    case DOCUMENTS = 'documents';
    case TECHNICAL_DRAWINGS = 'technical_drawings';
    case CERTIFICATIONS = 'certifications';
    
    // Mobile & Field Operations
    case MOBILE_ACCESS = 'mobile_access';
    case FIELD_OPERATIONS = 'field_operations';
    case EQUIPMENT_MANAGEMENT = 'equipment_management';
    
    // Reporting & Analytics
    case REPORTING = 'reporting';
    case ANALYTICS = 'analytics';
    case EXPORTS = 'exports';
    
    // System Administration
    case SYSTEM_ADMIN = 'system_admin';
    case API_ACCESS = 'api_access';
    case BACKUP_RESTORE = 'backup_restore';
}
```

### **2. Romanian Construction Industry Role Templates**

**Predefined Role System:**
```php
// Add to RoleService
public function createDefaultConstructionRoles(Workspace $workspace): array
{
    $roles = [
        'workspace_owner' => [
            'display_name' => 'Workspace Owner',
            'description' => 'Full workspace control and management',
            'permissions' => ['*'], // All permissions
            'color' => 'danger',
            'icon' => 'heroicon-o-crown',
        ],
        
        'project_manager' => [
            'display_name' => 'Project Manager',
            'description' => 'Manages projects, contracts, and teams',
            'permissions' => [
                'contracts.*', 'work_reports.approve', 'executors.manage',
                'users.invite', 'project_management.*', 'financial.view',
                'reporting.*', 'construction_sites.*'
            ],
            'color' => 'warning',
            'icon' => 'heroicon-o-briefcase',
        ],
        
        'site_supervisor' => [
            'display_name' => 'Site Supervisor',
            'description' => 'Supervises on-site work and approves reports',
            'permissions' => [
                'work_reports.*', 'quality_control.*', 'safety_management.*',
                'executors.view', 'progress_tracking.*', 'mobile_access.*',
                'field_operations.*'
            ],
            'color' => 'info',
            'icon' => 'heroicon-o-hard-hat',
        ],
        
        'quality_inspector' => [
            'display_name' => 'Quality Inspector',
            'description' => 'Performs quality inspections and compliance checks',
            'permissions' => [
                'quality_control.*', 'inspections.*', 'compliance.*',
                'work_reports.inspect', 'certifications.*', 'technical_drawings.view'
            ],
            'color' => 'success',
            'icon' => 'heroicon-o-shield-check',
        ],
        
        'safety_officer' => [
            'display_name' => 'Safety Officer',
            'description' => 'Manages workplace safety and regulatory compliance',
            'permissions' => [
                'safety_management.*', 'compliance.*', 'inspections.safety',
                'work_reports.safety_review', 'building_permits.safety'
            ],
            'color' => 'danger',
            'icon' => 'heroicon-o-shield-exclamation',
        ],
        
        'financial_manager' => [
            'display_name' => 'Financial Manager',
            'description' => 'Manages project finances and payments',
            'permissions' => [
                'financial.*', 'payments.*', 'invoicing.*', 'budget_management.*',
                'contracts.financial', 'reporting.financial'
            ],
            'color' => 'success',
            'icon' => 'heroicon-o-currency-dollar',
        ],
        
        'field_worker' => [
            'display_name' => 'Field Worker',
            'description' => 'On-site worker with mobile reporting access',
            'permissions' => [
                'work_reports.create', 'work_reports.view',
                'mobile_access.*', 'field_operations.basic',
                'progress_tracking.update'
            ],
            'color' => 'primary',
            'icon' => 'heroicon-o-wrench-screwdriver',
        ],
        
        'architect' => [
            'display_name' => 'Architect',
            'description' => 'Reviews technical aspects and design compliance',
            'permissions' => [
                'technical_drawings.*', 'building_permits.technical',
                'quality_control.design', 'compliance.design',
                'work_reports.technical_review'
            ],
            'color' => 'info',
            'icon' => 'heroicon-o-square-3-stack-3d',
        ],
        
        'document_manager' => [
            'display_name' => 'Document Manager',
            'description' => 'Manages project documentation and compliance',
            'permissions' => [
                'documents.*', 'certifications.*', 'building_permits.*',
                'technical_drawings.manage', 'compliance.documentation'
            ],
            'color' => 'secondary',
            'icon' => 'heroicon-o-document-text',
        ],
        
        'client_observer' => [
            'display_name' => 'Client Observer',
            'description' => 'Read-only access for client representatives',
            'permissions' => [
                'work_reports.view', 'contracts.view', 'progress_tracking.view',
                'documents.view', 'reporting.view'
            ],
            'color' => 'gray',
            'icon' => 'heroicon-o-eye',
        ],
    ];
    
    $createdRoles = [];
    foreach ($roles as $roleName => $roleData) {
        $createdRoles[] = $this->createRoleWithTemplate($workspace, $roleName, $roleData);
    }
    
    return $createdRoles;
}
```

### **3. Enhanced Permission System for Construction**

**Comprehensive Permission Set:**
```php
// Enhanced PermissionSeeder
public function run(): void
{
    $workspace = Workspace::findOrFail(1);
    
    $permissions = [
        PermissionCategory::PROJECT_MANAGEMENT->value => [
            'projects.view' => 'View project details and overview',
            'projects.create' => 'Create new construction projects',
            'projects.edit' => 'Edit project information and settings',
            'projects.delete' => 'Archive or delete projects',
            'projects.manage_timeline' => 'Modify project timelines and milestones',
            'projects.manage_budget' => 'Modify project budgets and financial planning',
        ],
        
        PermissionCategory::CONTRACTS->value => [
            'contracts.view' => 'View contracts and contract details',
            'contracts.create' => 'Create new contracts with executors',
            'contracts.edit' => 'Edit existing contracts and terms',
            'contracts.approve' => 'Approve contracts for execution',
            'contracts.terminate' => 'Terminate contracts',
            'contracts.financial' => 'View and manage contract financials',
            'contract_annexes.create' => 'Create contract amendments',
            'contract_annexes.approve' => 'Approve contract amendments',
        ],
        
        PermissionCategory::WORK_REPORTS->value => [
            'work_reports.view' => 'View all work reports',
            'work_reports.create' => 'Create new work reports',
            'work_reports.edit' => 'Edit draft work reports',
            'work_reports.submit' => 'Submit work reports for approval',
            'work_reports.approve' => 'Approve submitted work reports',
            'work_reports.reject' => 'Reject work reports with feedback',
            'work_reports.lock' => 'Lock approved work reports',
            'work_reports.inspect' => 'Perform quality inspections on work',
            'work_reports.safety_review' => 'Review safety compliance in reports',
            'work_reports.technical_review' => 'Review technical aspects of work',
        ],
        
        PermissionCategory::QUALITY_CONTROL->value => [
            'quality.inspect' => 'Perform quality inspections',
            'quality.approve' => 'Approve quality inspection results',
            'quality.create_checklist' => 'Create quality control checklists',
            'quality.manage_standards' => 'Manage quality standards and procedures',
            'quality.issue_certificates' => 'Issue quality compliance certificates',
        ],
        
        PermissionCategory::SAFETY_MANAGEMENT->value => [
            'safety.inspect' => 'Perform safety inspections',
            'safety.incident_report' => 'Create and manage safety incident reports',
            'safety.training_records' => 'Manage safety training records',
            'safety.equipment_check' => 'Perform safety equipment checks',
            'safety.site_access' => 'Control site access and safety protocols',
        ],
        
        PermissionCategory::FINANCIAL->value => [
            'financial.view_budgets' => 'View project budgets and financial summaries',
            'financial.manage_budgets' => 'Create and modify project budgets',
            'financial.view_costs' => 'View actual costs and expenses',
            'financial.approve_expenses' => 'Approve project expenses',
            'financial.generate_invoices' => 'Generate invoices for work completed',
            'payments.view' => 'View payment status and history',
            'payments.process' => 'Process payments to executors',
            'payments.approve' => 'Approve payment requests',
        ],
        
        PermissionCategory::MOBILE_ACCESS->value => [
            'mobile.access' => 'Access mobile application',
            'mobile.offline_sync' => 'Use offline functionality and sync',
            'mobile.gps_tracking' => 'Use GPS tracking features',
            'mobile.photo_upload' => 'Upload photos from mobile device',
            'mobile.quick_report' => 'Create quick work reports from mobile',
        ],
        
        PermissionCategory::REPORTING->value => [
            'reports.project_status' => 'Generate project status reports',
            'reports.financial' => 'Generate financial reports',
            'reports.progress' => 'Generate progress reports',
            'reports.compliance' => 'Generate compliance reports',
            'reports.custom' => 'Create custom reports and dashboards',
            'exports.excel' => 'Export data to Excel format',
            'exports.pdf' => 'Export reports to PDF format',
        ],
        
        PermissionCategory::SYSTEM_ADMIN->value => [
            'system.backup' => 'Perform system backups',
            'system.logs' => 'View system logs and debugging information',
            'system.maintenance' => 'Perform system maintenance tasks',
            'api.access' => 'Access API endpoints',
            'api.manage' => 'Manage API keys and integrations',
            'impersonate.users' => 'Impersonate other users for support',
        ],
    ];
    
    // Create permissions with proper workspace scoping
    foreach ($permissions as $category => $categoryPermissions) {
        foreach ($categoryPermissions as $permissionName => $description) {
            Permission::updateOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
                'workspace_id' => $workspace->id,
            ], [
                'category' => $category,
                'description' => $description,
            ]);
        }
    }
}
```

### **4. Enhanced Role Form with Professional Features**

**Advanced Role Management Interface:**
```php
public static function configure(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Role Information')
            ->description('Basic role details and identification')
            ->icon('heroicon-o-identification')
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Role Name (Internal)')
                        ->required()
                        ->maxLength(255)
                        ->alphaDash()
                        ->unique(/* ... existing validation */)
                        ->helperText('Internal name: lowercase-with-dashes')
                        ->placeholder('e.g., site-supervisor'),
                        
                    TextInput::make('display_name')
                        ->label('Display Name')
                        ->required()
                        ->maxLength(255)
                        ->helperText('User-friendly name shown in interface')
                        ->placeholder('e.g., Site Supervisor'),
                ]),
                
                Textarea::make('description')
                    ->label('Role Description')
                    ->placeholder('Describe the responsibilities and scope of this role...')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]),
            
        Section::make('Role Template')
            ->description('Start from a predefined construction role template')
            ->icon('heroicon-o-document-duplicate')
            ->visible(fn($context) => $context === 'create')
            ->schema([
                Select::make('role_template')
                    ->label('Use Template')
                    ->options([
                        'project_manager' => 'Project Manager - Full project control',
                        'site_supervisor' => 'Site Supervisor - Field supervision',
                        'quality_inspector' => 'Quality Inspector - Quality control',
                        'safety_officer' => 'Safety Officer - Safety compliance',
                        'financial_manager' => 'Financial Manager - Budget control',
                        'field_worker' => 'Field Worker - Mobile reporting',
                        'architect' => 'Architect - Technical review',
                        'client_observer' => 'Client Observer - Read-only access',
                    ])
                    ->afterStateUpdated(fn($state, Set $set) => $this->applyRoleTemplate($state, $set))
                    ->helperText('Select a template to automatically configure permissions'),
            ]),
            
        Section::make('Permission Matrix')
            ->description('Visual overview of role capabilities')
            ->icon('heroicon-o-table-cells')
            ->schema([
                ViewField::make('permission_matrix')
                    ->view('components.permission-matrix')
                    ->viewData(fn($record) => [
                        'role' => $record,
                        'categories' => PermissionCategory::cases(),
                        'permissions' => $this->getPermissionMatrix($record),
                    ]),
            ]),
            
        ...self::getDynamicPermissionSections(),
        
        Section::make('Role Settings')
            ->description('Additional role configuration')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Grid::make(3)->schema([
                    Toggle::make('is_default')
                        ->label('Default Role')
                        ->helperText('Automatically assign to new workspace members'),
                        
                    Toggle::make('requires_approval')
                        ->label('Requires Approval')
                        ->helperText('Role assignments require admin approval'),
                        
                    Toggle::make('is_temporary')
                        ->label('Temporary Role')
                        ->helperText('Role has expiration date'),
                ]),
                
                DateTimePicker::make('expires_at')
                    ->label('Role Expires')
                    ->native(false)
                    ->visible(fn(Get $get) => $get('is_temporary')),
            ]),
    ]);
}
```

### **5. Advanced Role Table with Professional Features**

**Enhanced RolesTable:**
```php
return $table
    ->striped()
    ->searchable(['name', 'display_name', 'description'])
    ->paginated([10, 25, 50])
    ->columns([
        TextColumn::make('role_info')
            ->label('Role')
            ->formatStateUsing(fn($record) => new HtmlString(
                '<div class="flex items-center space-x-3">' .
                '<div class="flex-shrink-0">' .
                '<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-' . $record->color . '-100">' .
                '<svg class="w-4 h-4 text-' . $record->color . '-600">' . $record->icon . '</svg>' .
                '</span>' .
                '</div>' .
                '<div>' .
                '<div class="font-medium">' . $record->display_name . '</div>' .
                '<div class="text-sm text-gray-500">' . $record->name . '</div>' .
                '</div>' .
                '</div>'
            ))
            ->searchable(['name', 'display_name'])
            ->sortable('display_name'),
            
        TextColumn::make('description')
            ->limit(60)
            ->tooltip(fn($record) => $record->description)
            ->placeholder('No description'),
            
        TextColumn::make('permissions_summary')
            ->label('Permissions')
            ->formatStateUsing(fn($record) => $this->getPermissionSummary($record))
            ->badge()
            ->separator(','),
            
        TextColumn::make('users_count')
            ->label('Users')
            ->counts('users')
            ->badge()
            ->colors([
                'gray' => 0,
                'primary' => fn($value) => $value > 0,
            ]),
            
        TextColumn::make('permission_count')
            ->label('Total Permissions')
            ->getStateUsing(fn($record) => $record->permissions()->count())
            ->badge()
            ->color('info'),
            
        TextColumn::make('last_assigned')
            ->label('Last Assigned')
            ->getStateUsing(fn($record) => 
                $record->users()->latest('updated_at')->first()?->updated_at)
            ->dateTime()
            ->since()
            ->placeholder('Never'),
            
        TextColumn::make('created_at')
            ->label('Created')
            ->dateTime()
            ->since()
            ->toggleable(isToggledHiddenByDefault: true),
    ])
    ->filters([
        SelectFilter::make('permission_category')
            ->label('Has Permissions In')
            ->options(PermissionCategory::options())
            ->query(function ($query, $data) {
                if (!$data['value']) return $query;
                
                return $query->whereHas('permissions', function ($q) use ($data) {
                    $q->where('category', $data['value']);
                });
            }),
            
        Filter::make('has_users')
            ->label('Roles in Use')
            ->query(fn($query) => $query->has('users')),
            
        Filter::make('system_roles')
            ->label('System Roles')
            ->query(fn($query) => $query->whereIn('name', [
                'workspace_owner', 'system_admin'
            ])),
    ])
    ->actions([
        ActionGroup::make([
            ViewAction::make('preview')
                ->label('Preview Capabilities')
                ->icon('heroicon-o-eye')
                ->modalContent(fn($record) => view('modals.role-capabilities-preview', [
                    'role' => $record,
                    'permissions' => $record->permissions,
                    'capabilities' => $this->getRoleCapabilities($record),
                ]))
                ->modalWidth('5xl'),
                
            Action::make('duplicate')
                ->label('Duplicate Role')
                ->icon('heroicon-o-document-duplicate')
                ->color('secondary')
                ->form([
                    TextInput::make('new_name')
                        ->label('New Role Name')
                        ->required()
                        ->alphaDash(),
                    TextInput::make('new_display_name')
                        ->label('New Display Name')
                        ->required(),
                ])
                ->action(fn($record, $data) => $this->duplicateRole($record, $data)),
                
            EditAction::make()->icon('heroicon-o-pencil'),
            
            Action::make('assign_to_users')
                ->label('Assign to Users')
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->form([
                    CheckboxList::make('user_ids')
                        ->options(fn() => $this->getWorkspaceUsers())
                        ->columns(2)
                ])
                ->action(fn($record, $data) => $this->assignRoleToUsers($record, $data)),
        ]),
    ])
    ->bulkActions([
        BulkActionGroup::make([
            BulkAction::make('bulk_permission_grant')
                ->label('Grant Permission')
                ->icon('heroicon-o-plus-circle')
                ->form([
                    Select::make('permission_id')
                        ->options(fn() => $this->getAvailablePermissions())
                        ->required()
                ])
                ->action(fn($records, $data) => $this->bulkGrantPermission($records, $data)),
                
            BulkAction::make('export_roles')
                ->label('Export Role Matrix')
                ->icon('heroicon-o-document-arrow-down')
                ->action(fn($records) => $this->exportRoleMatrix($records)),
                
            DeleteBulkAction::make()->requiresConfirmation(),
        ]),
    ]);
```

### **6. Permission Matrix Visualization**

**Permission Matrix Component:**
```php
// resources/views/components/permission-matrix.blade.php
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Permission Category
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    View
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Create
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Edit
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Delete
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Approve
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Manage
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $category->label() }}
                    </td>
                    @foreach(['view', 'create', 'edit', 'delete', 'approve', 'manage'] as $action)
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if(isset($permissions[$category->value][$action]))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

### **7. Missing Critical Features for Construction Industry**

**Role Hierarchy System:**
```php
// Add to Role model
protected $fillable = [
    // ... existing
    'parent_role_id',          // Role inheritance
    'hierarchy_level',         // 1=Owner, 2=Manager, 3=Supervisor, 4=Worker
    'can_delegate',            // Can delegate permissions to others
    'max_delegation_level',    // Maximum delegation depth
    'requires_certification',  // Role requires professional certification
    'minimum_experience_months', // Minimum experience required
];

public function parentRole(): BelongsTo
{
    return $this->belongsTo(Role::class, 'parent_role_id');
}

public function childRoles(): HasMany
{
    return $this->hasMany(Role::class, 'parent_role_id');
}

public function getInheritedPermissions(): Collection
{
    $permissions = $this->permissions;
    
    if ($this->parentRole) {
        $permissions = $permissions->merge($this->parentRole->getInheritedPermissions());
    }
    
    return $permissions->unique('id');
}
```

**Romanian Construction Compliance:**
```php
// Add to Role model  
protected $fillable = [
    // ... existing
    'anpc_license_required',      // ANPC professional license requirement
    'safety_certification_required', // Safety certification requirement
    'technical_competency',       // Technical competency level required
    'supervision_authority',      // Can supervise which types of work
    'signature_authority',        // Digital signature authority level
    'financial_limit',            // Maximum financial approval limit
];

public function hasSignatureAuthority(string $documentType): bool
{
    $authorityMap = [
        'work_report' => 1,
        'quality_inspection' => 2,
        'contract_approval' => 3,
        'financial_approval' => 4,
        'final_acceptance' => 5,
    ];
    
    return $this->signature_authority >= ($authorityMap[$documentType] ?? 0);
}
```

### **8. Advanced Permission Management Features**

**Permission Analytics Dashboard:**
```php
// Add to PermissionResource
protected function getHeaderWidgets(): array
{
    return [
        PermissionUsageWidget::class,
        RoleEffectivenessWidget::class,
    ];
}

class PermissionUsageWidget extends Widget
{
    protected static string $view = 'widgets.permission-usage';
    
    public function getViewData(): array
    {
        $workspace = Filament::getTenant();
        
        return [
            'totalPermissions' => Permission::forWorkspace($workspace)->count(),
            'activePermissions' => Permission::forWorkspace($workspace)
                ->whereHas('roles')->count(),
            'unusedPermissions' => Permission::forWorkspace($workspace)
                ->whereDoesntHave('roles')->count(),
            'categoryDistribution' => Permission::forWorkspace($workspace)
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category'),
            'mostUsedPermissions' => Permission::forWorkspace($workspace)
                ->withCount('roles')
                ->orderByDesc('roles_count')
                ->limit(10)
                ->get(),
        ];
    }
}
```

**Role Template Management:**
```php
// Add action to RolesTable
Action::make('create_from_template')
    ->label('Create from Template')
    ->icon('heroicon-o-document-plus')
    ->color('success')
    ->form([
        Select::make('template')
            ->options([
                'project_manager' => 'Project Manager',
                'site_supervisor' => 'Site Supervisor',
                'quality_inspector' => 'Quality Inspector',
                // ... other templates
            ])
            ->required(),
        TextInput::make('custom_name')->required(),
        TextInput::make('custom_display_name')->required(),
    ])
    ->action(fn($data) => $this->createRoleFromTemplate($data)),
```

### **9. Enhanced Security & Audit Features**

**Permission Change Tracking:**
```php
// New RolePermissionHistory model
class RolePermissionHistory extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
        'action',              // 'granted', 'revoked'
        'changed_by',          // User who made the change
        'reason',              // Reason for change
        'changed_at',
    ];
    
    protected $casts = [
        'changed_at' => 'datetime',
    ];
}

// Add to RoleService
public function grantPermissionWithAudit(Role $role, Permission $permission, User $changedBy, string $reason = null): void
{
    DB::transaction(function () use ($role, $permission, $changedBy, $reason) {
        $role->givePermissionTo($permission);
        
        RolePermissionHistory::create([
            'role_id' => $role->id,
            'permission_id' => $permission->id,
            'action' => 'granted',
            'changed_by' => $changedBy->id,
            'reason' => $reason,
            'changed_at' => now(),
        ]);
    });
}
```

**Role Security Validation:**
```php
// Add to RoleService
public function validateRoleSecurityRules(Role $role, array $permissionIds): array
{
    $warnings = [];
    $errors = [];
    
    // Check for dangerous permission combinations
    $dangerousPerms = ['system.backup', 'impersonate.users', 'api.manage'];
    $hasDangerous = Permission::whereIn('id', $permissionIds)
        ->whereIn('name', $dangerousPerms)
        ->exists();
        
    if ($hasDangerous && $role->users()->count() > 5) {
        $warnings[] = 'Assigning system admin permissions to role with many users';
    }
    
    // Check for minimum permission requirements
    if (in_array($role->name, ['site_supervisor', 'project_manager'])) {
        $requiredPerms = ['work_reports.view', 'contracts.view'];
        $hasRequired = Permission::whereIn('id', $permissionIds)
            ->whereIn('name', $requiredPerms)
            ->count() === count($requiredPerms);
            
        if (!$hasRequired) {
            $errors[] = 'Role missing required permissions for construction supervision';
        }
    }
    
    return compact('warnings', 'errors');
}
```

### **10. Romanian Construction Industry Integration**

**Professional Certification Integration:**
```php
// Add to Role model
public function requiredCertifications(): BelongsToMany
{
    return $this->belongsToMany(ProfessionalCertification::class)
        ->withTimestamps();
}

public function canUserHaveRole(User $user): bool
{
    // Check certification requirements
    $requiredCerts = $this->requiredCertifications;
    
    foreach ($requiredCerts as $cert) {
        if (!$user->hasCertification($cert)) {
            return false;
        }
    }
    
    // Check minimum experience
    if ($this->minimum_experience_months > 0) {
        $userExperience = $user->getTotalExperienceMonths();
        if ($userExperience < $this->minimum_experience_months) {
            return false;
        }
    }
    
    return true;
}
```

**ANPC License Integration:**
```php
// Romanian construction professional licensing
public function validateANPCLicense(User $user, Role $role): bool
{
    if (!$role->anpc_license_required) return true;
    
    $license = $user->anpcLicense();
    
    return $license && 
           $license->is_valid && 
           $license->covers_activity($role->technical_competency);
}
```

## **Priority Implementation Roadmap**

### **Phase 1 (Week 1): Enhanced Permission Categories**
1. Expand PermissionCategory enum with comprehensive construction categories
2. Create detailed permission set for all construction operations
3. Update PermissionSeeder with realistic construction permissions
4. Add permission descriptions and help text

### **Phase 2 (Week 2): Role Templates & Professional Features**
1. Implement default construction role templates
2. Enhanced RoleForm with template selection and permission matrix
3. Advanced RolesTable with capability summaries and user counts
4. Role duplication and template management

### **Phase 3 (Week 3): Security & Audit Features**
1. Implement role permission change tracking
2. Add role security validation rules
3. Permission usage analytics and reporting
4. Role effectiveness monitoring

### **Phase 4 (Week 4): Romanian Construction Integration**
1. Professional certification requirements for roles
2. ANPC license validation integration
3. Role hierarchy and permission inheritance
4. Construction industry compliance automation

## **Expected Business Impact**

- **Professional Authorization**: Industry-standard role definitions for Romanian construction
- **Enhanced Security**: Granular permission control with audit trails
- **Operational Efficiency**: Template-based role creation reduces setup time
- **Compliance Ready**: Built-in professional certification and licensing requirements
- **Better User Management**: Clear role capabilities and permission visualization
- **Regulatory Compliance**: ANPC and Romanian construction law compliance integration

## **Critical Missing Features to Address**

1. **Comprehensive Permission Set** - Only 4 basic categories vs. full construction operations
2. **Role Templates** - No predefined construction industry role templates
3. **Permission Matrix Visualization** - Missing capability overview interface
4. **Romanian Professional Requirements** - No ANPC license or certification integration
5. **Role Analytics** - No usage tracking or effectiveness monitoring
6. **Audit Trail** - Missing permission change history and security tracking

## **Integration with Construction Operations**

**Contract Integration:**
- Role-based contract approval workflows
- Financial approval limits based on role hierarchy
- Technical review requirements for architects/engineers

**Work Report Integration:**
- Supervisor approval requirements based on role authority
- Quality inspector assignment based on role qualifications
- Mobile access permissions for field workers

**Safety & Compliance Integration:**
- Role-based safety inspection authority
- Compliance review requirements by professional qualification
- Regulatory reporting access based on certification levels