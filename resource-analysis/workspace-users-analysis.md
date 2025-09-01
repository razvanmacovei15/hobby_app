# Workspace Users Resource - Analysis & Improvement Recommendations

## **Current Implementation Overview**

The workspace user management system handles multi-tenancy through a dual approach: basic WorkspaceUser pivot model for membership and a separate WorkspaceInvitation system for user onboarding with sophisticated role-based permissions using Spatie Laravel Permission.

### **Files Structure**
```
app/Filament/Resources/WorkspaceUsers/
├── WorkspaceUserResource.php        # Basic workspace member management
├── Pages/
│   ├── CreateWorkspaceUser.php     # Simple user-workspace association
│   ├── EditWorkspaceUser.php       # Basic editing
│   └── ListWorkspaceUsers.php      # Member listing
├── Schemas/
│   └── WorkspaceUserForm.php       # Minimal form (user_id, workspace_id, is_default)
└── Tables/
    └── WorkspaceUsersTable.php     # Table with role display logic

app/Filament/Resources/WorkspaceInvitations/
├── WorkspaceInvitationResource.php # Sophisticated invitation management
├── Pages/                          # Complete CRUD with navigation badge
├── Schemas/
│   ├── WorkspaceInvitationForm.php # Read-only invitation display
│   └── WorkspaceInvitationInfolist.php
└── Tables/
    └── WorkspaceInvitationsTable.php # Advanced table with status actions

app/Models/
├── WorkspaceUser.php               # Simple pivot model (minimal fields)
├── WorkspaceInvitation.php         # Complex invitation model with polymorphic design
├── Permission/Role.php             # Workspace-scoped Spatie role extension
└── User.php                        # Enhanced with workspace role methods
```

## **Business Logic Analysis**

### **Strengths ✅**

**Advanced Permission System:**
- **Spatie Integration**: Proper workspace-scoped roles and permissions system
- **Polymorphic Invitations**: WorkspaceInvitation supports both User and Company invitations
- **Role Management**: Sophisticated workspace-specific role assignment and checking
- **Multi-tenant Security**: Proper workspace isolation with role scoping

**Invitation System Excellence:**
- **Token-based Security**: Secure random token generation with expiration
- **Status Tracking**: Comprehensive invitation lifecycle (pending → accepted/expired/declined)
- **Email Integration**: Built-in notification system with custom messages
- **Resend Functionality**: Professional invitation management with retry logic
- **Navigation Badge**: Real-time pending invitation count display

**Technical Architecture:**
- **Clean Separation**: WorkspaceUser (membership) vs WorkspaceInvitation (onboarding) concerns
- **Service Layer**: Proper interface-based service architecture
- **Advanced Queries**: Optimized queries with eager loading and workspace scoping
- **User Experience**: Professional table actions and status management

### **Critical Issues ⚠️**

**Disconnected Systems:**
- **Dual Management**: WorkspaceUsers and WorkspaceInvitations are separate resources without integration
- **Incomplete WorkspaceUser Model**: Missing status, invitation tracking, role history
- **Manual Process**: No automated flow from invitation → workspace user approval
- **Data Duplication**: Invitation data not properly integrated with workspace membership

**Missing Core Features (PROJECT_PLAN.md Requirements):**
- **No User Status Workflow**: WorkspaceUser lacks pending/approved/suspended states
- **Missing Role History**: No audit trail for role changes
- **No Department Support**: Missing organizational structure within workspaces
- **Limited User Profiles**: Missing job titles, departments, access levels

**User Experience Gaps:**
- **Fragmented Interface**: Users must navigate between two separate resources
- **Basic WorkspaceUser Form**: Too minimal for professional user management
- **Missing User Dashboard**: No overview of user workspace activities
- **No Bulk Operations**: Limited bulk user management capabilities

## **Improvement Recommendations**

### **1. Unified Workspace User Management System (High Priority)**

**Enhanced WorkspaceUser Model:**
```php
protected $fillable = [
    'workspace_id',
    'user_id',
    'status',                    // WorkspaceUserStatus enum
    'job_title',                 // User's role in the organization
    'department',                // Department/team assignment
    'access_level',              // Basic/Standard/Admin access level
    'invited_by',                // Who invited this user
    'invitation_token',          // Integration with invitation system
    'invited_at',                // When invitation was sent
    'accepted_at',               // When user joined workspace
    'suspended_at',              // If user is temporarily suspended
    'last_active_at',            // Last activity timestamp
    'is_default',                // Default workspace for user
    'notes',                     // Admin notes about user
];

protected $casts = [
    'status' => WorkspaceUserStatus::class,
    'invited_at' => 'datetime',
    'accepted_at' => 'datetime',
    'suspended_at' => 'datetime',
    'last_active_at' => 'datetime',
];
```

**New WorkspaceUserStatus Enum:**
```php
enum WorkspaceUserStatus: string {
    case INVITATION_SENT = 'invitation_sent';
    case PENDING_APPROVAL = 'pending_approval';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case INACTIVE = 'inactive';
    case REMOVED = 'removed';
}
```

### **2. Professional Workspace User Form Enhancement**

**Comprehensive User Management Form:**
```php
public static function configure(Schema $schema): Schema
{
    return $schema->components([
        Section::make('User Information')
            ->description('Basic user details and contact information')
            ->icon('heroicon-o-user')
            ->schema([
                Select::make('user_id')
                    ->label('Select User')
                    ->relationship('user', 'email')
                    ->getOptionLabelFromRecordUsing(fn($record) => 
                        "{$record->getFilamentName()} ({$record->email})")
                    ->searchable(['first_name', 'last_name', 'email'])
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('first_name')->required(),
                        TextInput::make('last_name')->required(),
                        TextInput::make('email')->email()->required(),
                        // Password will be set via invitation acceptance
                    ])
                    ->createOptionAction(fn($action) => 
                        $action->modalHeading('Create New User')
                               ->modalWidth('md')),
                               
                TextInput::make('job_title')
                    ->label('Job Title')
                    ->placeholder('e.g., Site Supervisor, Project Manager')
                    ->maxLength(100),
                    
                Select::make('department')
                    ->label('Department')
                    ->options([
                        'management' => 'Management',
                        'engineering' => 'Engineering',
                        'construction' => 'Construction',
                        'quality_control' => 'Quality Control',
                        'safety' => 'Safety & Compliance',
                        'administration' => 'Administration',
                        'finance' => 'Finance',
                    ])
                    ->native(false),
            ])->columns(2),
            
        Section::make('Access & Permissions')
            ->description('User access level and role assignments')
            ->icon('heroicon-o-key')
            ->schema([
                Select::make('access_level')
                    ->label('Access Level')
                    ->options([
                        'basic' => 'Basic - Read-only access',
                        'standard' => 'Standard - Full user access',
                        'admin' => 'Admin - Management access',
                        'owner' => 'Owner - Full control',
                    ])
                    ->required()
                    ->default('standard')
                    ->live()
                    ->helperText('Determines base permission level'),
                    
                CheckboxList::make('role_ids')
                    ->label('Additional Roles')
                    ->options(fn() => $this->getWorkspaceRoles())
                    ->descriptions([
                        'project_manager' => 'Can manage projects and assign tasks',
                        'site_supervisor' => 'Can approve work reports and manage field workers',
                        'quality_inspector' => 'Can perform quality inspections',
                        'safety_officer' => 'Can manage safety compliance',
                    ])
                    ->columns(2),
            ])->columns(1),
            
        Section::make('Workspace Settings')
            ->description('User-specific workspace configuration')
            ->icon('heroicon-o-cog-6-tooth')
            ->schema([
                Select::make('status')
                    ->options(WorkspaceUserStatus::options())
                    ->default(WorkspaceUserStatus::INVITATION_SENT)
                    ->required()
                    ->disabled(fn($context) => $context === 'create'),
                    
                Toggle::make('is_default')
                    ->label('Default Workspace')
                    ->helperText('This workspace will be selected by default for this user'),
                    
                DateTimePicker::make('access_expires_at')
                    ->label('Access Expires')
                    ->native(false)
                    ->helperText('Optional: Set expiration date for temporary access'),
                    
                Textarea::make('notes')
                    ->label('Admin Notes')
                    ->placeholder('Internal notes about this user...')
                    ->maxLength(500),
            ])->columns(2),
    ]);
}
```

### **3. Enhanced Table with Professional Features**

**Advanced WorkspaceUsersTable:**
```php
return $table
    ->striped()
    ->searchable(['user.first_name', 'user.last_name', 'user.email', 'job_title'])
    ->paginated([10, 25, 50])
    ->poll('60s')  // Real-time status updates
    ->columns([
        ImageColumn::make('user.avatar')
            ->label('')
            ->circular()
            ->defaultImageUrl(fn($record) => 
                'https://ui-avatars.com/api/?name=' . urlencode($record->user->getFilamentName()))
            ->size(40),
            
        TextColumn::make('user_info')
            ->label('User')
            ->formatStateUsing(fn($record) => new HtmlString(
                '<div class="flex flex-col">' .
                '<span class="font-medium">' . $record->user->getFilamentName() . '</span>' .
                '<span class="text-sm text-gray-500">' . $record->user->email . '</span>' .
                ($record->job_title ? '<span class="text-xs text-gray-400">' . $record->job_title . '</span>' : '') .
                '</div>'
            ))
            ->searchable(['user.first_name', 'user.last_name', 'user.email'])
            ->sortable(['user.first_name']),
            
        TextColumn::make('department')
            ->badge()
            ->colors([
                'primary' => 'management',
                'success' => 'engineering',
                'warning' => 'construction',
                'info' => 'quality_control',
                'danger' => 'safety',
            ]),
            
        TextColumn::make('roles')
            ->label('Roles')
            ->formatStateUsing(function ($record) {
                $workspace = Filament::getTenant();
                $roles = $record->user->getWorkspaceRoles($workspace);
                return $roles->pluck('display_name')->implode(', ');
            })
            ->badge()
            ->separator(','),
            
        TextColumn::make('status')
            ->badge()
            ->colors([
                'gray' => WorkspaceUserStatus::INVITATION_SENT,
                'warning' => WorkspaceUserStatus::PENDING_APPROVAL,
                'success' => WorkspaceUserStatus::ACTIVE,
                'danger' => WorkspaceUserStatus::SUSPENDED,
                'secondary' => WorkspaceUserStatus::INACTIVE,
            ]),
            
        TextColumn::make('access_level')
            ->badge()
            ->colors([
                'gray' => 'basic',
                'primary' => 'standard', 
                'warning' => 'admin',
                'danger' => 'owner',
            ]),
            
        TextColumn::make('last_active_at')
            ->label('Last Seen')
            ->dateTime()
            ->since()
            ->placeholder('Never')
            ->toggleable(),
            
        TextColumn::make('accepted_at')
            ->label('Joined')
            ->dateTime()
            ->since()
            ->placeholder('Pending')
            ->toggleable(isToggledHiddenByDefault: true),
            
        IconColumn::make('is_default')
            ->label('Default')
            ->boolean()
            ->toggleable(),
    ])
    ->filters([
        SelectFilter::make('status')
            ->options(WorkspaceUserStatus::options())
            ->multiple(),
            
        SelectFilter::make('access_level')
            ->options([
                'basic' => 'Basic',
                'standard' => 'Standard',
                'admin' => 'Admin',
                'owner' => 'Owner',
            ]),
            
        SelectFilter::make('department')
            ->options([
                'management' => 'Management',
                'engineering' => 'Engineering', 
                'construction' => 'Construction',
                'quality_control' => 'Quality Control',
                'safety' => 'Safety & Compliance',
                'administration' => 'Administration',
                'finance' => 'Finance',
            ]),
            
        Filter::make('active_users')
            ->query(fn($query) => $query->where('status', WorkspaceUserStatus::ACTIVE)),
            
        Filter::make('pending_approvals')
            ->query(fn($query) => $query->whereIn('status', [
                WorkspaceUserStatus::INVITATION_SENT,
                WorkspaceUserStatus::PENDING_APPROVAL
            ])),
    ])
    ->actions([
        ActionGroup::make([
            ViewAction::make()->icon('heroicon-o-eye'),
            
            Action::make('manage_roles')
                ->label('Manage Roles')
                ->icon('heroicon-o-key')
                ->color('primary')
                ->form([
                    CheckboxList::make('role_ids')
                        ->options(fn() => $this->getWorkspaceRoles())
                        ->default(fn($record) => 
                            $record->user->getWorkspaceRoles($record->workspace)->pluck('id'))
                ])
                ->action(fn($record, $data) => $this->updateUserRoles($record, $data)),
                
            Action::make('send_invitation')
                ->label('Send Invitation')
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->visible(fn($record) => $record->status === WorkspaceUserStatus::INVITATION_SENT)
                ->action(fn($record) => $this->sendInvitation($record)),
                
            Action::make('approve')
                ->label('Approve Access')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn($record) => $record->status === WorkspaceUserStatus::PENDING_APPROVAL)
                ->requiresConfirmation()
                ->action(fn($record) => $record->update(['status' => WorkspaceUserStatus::ACTIVE])),
                
            Action::make('suspend')
                ->label('Suspend User')
                ->icon('heroicon-o-pause-circle')
                ->color('warning')
                ->visible(fn($record) => $record->status === WorkspaceUserStatus::ACTIVE)
                ->requiresConfirmation()
                ->action(fn($record) => $record->update([
                    'status' => WorkspaceUserStatus::SUSPENDED,
                    'suspended_at' => now()
                ])),
                
            EditAction::make()->icon('heroicon-o-pencil'),
        ]),
    ])
    ->bulkActions([
        BulkActionGroup::make([
            BulkAction::make('bulk_approve')
                ->label('Approve Selected')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn($records) => $records->each->update(['status' => WorkspaceUserStatus::ACTIVE])),
                
            BulkAction::make('bulk_role_assignment')
                ->label('Assign Roles')
                ->icon('heroicon-o-key')
                ->form([
                    CheckboxList::make('role_ids')
                        ->options(fn() => $this->getWorkspaceRoles())
                ])
                ->action(fn($records, $data) => $this->bulkAssignRoles($records, $data)),
                
            BulkAction::make('export_users')
                ->label('Export User List')
                ->icon('heroicon-o-document-arrow-down')
                ->action(fn($records) => $this->exportUsers($records)),
                
            DeleteBulkAction::make()->label('Remove from Workspace'),
        ]),
    ]);
```

### **4. Integrated Invitation & User Management Flow**

**Unified User Creation Process:**
```php
// Enhanced CreateWorkspaceUser.php
protected function handleRecordCreation(array $data): Model
{
    return DB::transaction(function () use ($data) {
        $workspace = Filament::getTenant();
        $invitedBy = auth()->user();
        
        // Check if user already exists
        $existingUser = User::where('email', $data['email'])->first();
        
        if (!$existingUser) {
            // Create new user
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(16)), // Temporary password
            ]);
        } else {
            $user = $existingUser;
        }
        
        // Create workspace user record
        $workspaceUser = WorkspaceUser::create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'status' => $existingUser 
                ? WorkspaceUserStatus::PENDING_APPROVAL 
                : WorkspaceUserStatus::INVITATION_SENT,
            'job_title' => $data['job_title'],
            'department' => $data['department'],
            'access_level' => $data['access_level'],
            'invited_by' => $invitedBy->id,
            'invited_at' => now(),
            'invitation_token' => Str::random(64),
            'notes' => $data['notes'],
        ]);
        
        // Store roles for post-invitation assignment
        if (!empty($data['role_ids'])) {
            cache()->put(
                "workspace_user_roles_{$workspaceUser->invitation_token}",
                $data['role_ids'],
                now()->addDays(7)
            );
        }
        
        // Send appropriate notification
        if (!$existingUser) {
            $workspaceUser->sendInvitationEmail($data['custom_message'] ?? null);
        } else {
            $workspaceUser->sendAccessRequestNotification();
        }
        
        return $workspaceUser;
    });
}
```

### **5. Advanced User Dashboard & Analytics**

**Workspace User Overview Widget:**
```php
class WorkspaceUserOverviewWidget extends Widget
{
    protected static string $view = 'widgets.workspace-user-overview';
    
    public function getViewData(): array
    {
        $workspace = Filament::getTenant();
        
        return [
            'totalUsers' => WorkspaceUser::where('workspace_id', $workspace->id)->count(),
            'activeUsers' => WorkspaceUser::where('workspace_id', $workspace->id)
                ->where('status', WorkspaceUserStatus::ACTIVE)->count(),
            'pendingInvitations' => WorkspaceUser::where('workspace_id', $workspace->id)
                ->where('status', WorkspaceUserStatus::INVITATION_SENT)->count(),
            'pendingApprovals' => WorkspaceUser::where('workspace_id', $workspace->id)
                ->where('status', WorkspaceUserStatus::PENDING_APPROVAL)->count(),
            'recentJoins' => WorkspaceUser::where('workspace_id', $workspace->id)
                ->where('accepted_at', '>=', now()->subDays(7))
                ->with('user')
                ->latest('accepted_at')
                ->limit(5)
                ->get(),
            'roleDistribution' => $this->getRoleDistribution($workspace),
            'departmentDistribution' => $this->getDepartmentDistribution($workspace),
        ];
    }
}
```

**Enhanced ViewWorkspaceUser Page:**
```php
protected function getHeaderActions(): array
{
    return [
        Action::make('user_activity')
            ->label('View Activity')
            ->icon('heroicon-o-chart-bar')
            ->url(fn() => UserActivityResource::getUrl('view', ['record' => $this->record->user])),
            
        Action::make('send_message')
            ->label('Send Message')
            ->icon('heroicon-o-chat-bubble-left')
            ->form([
                Textarea::make('message')
                    ->required()
                    ->placeholder('Message to send to user...')
            ])
            ->action(fn($data) => $this->sendUserMessage($data)),
            
        ActionGroup::make([
            Action::make('change_access_level')
                ->label('Change Access Level')
                ->icon('heroicon-o-adjustments-horizontal')
                ->form([
                    Select::make('access_level')
                        ->options([
                            'basic' => 'Basic',
                            'standard' => 'Standard',
                            'admin' => 'Admin',
                            'owner' => 'Owner',
                        ])
                        ->default($this->record->access_level)
                ])
                ->action(fn($data) => $this->updateAccessLevel($data)),
                
            Action::make('reset_password')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->requiresConfirmation()
                ->action(fn() => $this->resetUserPassword()),
                
            Action::make('impersonate')
                ->label('Login as User')
                ->icon('heroicon-o-user-circle')
                ->visible(fn() => auth()->user()->hasWorkspacePermission($this->record->workspace, 'impersonate_users'))
                ->requiresConfirmation()
                ->action(fn() => $this->impersonateUser()),
        ])->label('User Actions'),
        
        EditAction::make()->icon('heroicon-o-pencil'),
    ];
}

protected function getHeaderWidgets(): array
{
    return [
        UserActivityWidget::class,
        UserPermissionsWidget::class,
    ];
}
```

### **6. Missing Permission & Role Management Features**

**Workspace-Specific Role Definition:**
```php
// Default workspace roles to create
public static function getDefaultWorkspaceRoles(): array
{
    return [
        'workspace_owner' => [
            'display_name' => 'Workspace Owner',
            'permissions' => ['*'], // All permissions
        ],
        'project_manager' => [
            'display_name' => 'Project Manager',
            'permissions' => [
                'contracts.view', 'contracts.create', 'contracts.edit',
                'work_reports.view', 'work_reports.approve',
                'executors.view', 'executors.manage',
                'users.view', 'users.invite',
            ],
        ],
        'site_supervisor' => [
            'display_name' => 'Site Supervisor',
            'permissions' => [
                'work_reports.view', 'work_reports.create', 'work_reports.edit',
                'executors.view',
                'contracts.view',
            ],
        ],
        'quality_inspector' => [
            'display_name' => 'Quality Inspector',
            'permissions' => [
                'work_reports.view', 'work_reports.inspect',
                'quality_checks.view', 'quality_checks.create',
                'contracts.view',
            ],
        ],
        'field_worker' => [
            'display_name' => 'Field Worker',
            'permissions' => [
                'work_reports.view', 'work_reports.create',
                'mobile.access',
            ],
        ],
        'observer' => [
            'display_name' => 'Observer',
            'permissions' => [
                'work_reports.view',
                'contracts.view',
                'dashboard.view',
            ],
        ],
    ];
}
```

**Permission Categories for Romanian Construction:**
```php
public static function getConstructionPermissions(): array
{
    return [
        'Project Management' => [
            'contracts.view', 'contracts.create', 'contracts.edit', 'contracts.approve',
            'construction_sites.view', 'construction_sites.manage',
            'building_permits.view', 'building_permits.manage',
        ],
        'Work Execution' => [
            'work_reports.view', 'work_reports.create', 'work_reports.edit', 'work_reports.approve',
            'executors.view', 'executors.manage',
            'services.view', 'services.manage',
        ],
        'Quality & Safety' => [
            'quality_checks.view', 'quality_checks.create', 'quality_checks.approve',
            'safety_inspections.view', 'safety_inspections.create',
            'compliance.view', 'compliance.manage',
        ],
        'Team Management' => [
            'users.view', 'users.invite', 'users.manage',
            'roles.view', 'roles.assign',
            'workspace.settings',
        ],
        'Financial' => [
            'payments.view', 'payments.create', 'payments.approve',
            'invoices.view', 'invoices.generate',
            'financial_reports.view',
        ],
        'Administration' => [
            'workspace.manage', 'workspace.settings',
            'system.backup', 'system.logs',
            'api.access', 'impersonate_users',
        ],
    ];
}
```

### **7. Mobile & Field Worker Optimization**

**Field Worker Interface:**
```php
// Add to WorkspaceUsersTable
->recordUrl(fn($record) => $record->access_level === 'field_worker' 
    ? route('mobile.dashboard') 
    : WorkspaceUserResource::getUrl('view', ['record' => $record]))
    
// Mobile-specific user features
protected function getMobileActions(): array
{
    return [
        Action::make('mobile_setup')
            ->label('Setup Mobile Access')
            ->icon('heroicon-o-device-phone-mobile')
            ->visible(fn($record) => in_array($record->access_level, ['field_worker', 'site_supervisor']))
            ->form([
                TextInput::make('device_name')
                    ->required()
                    ->placeholder('e.g., iPhone 12 Pro'),
                Toggle::make('offline_access')
                    ->label('Enable Offline Access'),
                Toggle::make('gps_tracking')
                    ->label('Enable GPS Tracking'),
            ])
            ->action(fn($record, $data) => $this->setupMobileAccess($record, $data)),
    ];
}
```

### **8. Advanced Workspace User Features**

**User Activity Tracking:**
```php
// Add to WorkspaceUser model
public function activities(): HasMany
{
    return $this->hasMany(UserActivity::class);
}

public function getLastActivityAttribute(): ?Carbon
{
    return $this->activities()->latest()->first()?->created_at;
}

public function getActivitySummary(int $days = 30): array
{
    return [
        'work_reports_created' => $this->user->workReports()
            ->where('created_at', '>=', now()->subDays($days))
            ->count(),
        'contracts_viewed' => $this->activities()
            ->where('activity_type', 'contract_viewed')
            ->where('created_at', '>=', now()->subDays($days))
            ->count(),
        'login_count' => $this->activities()
            ->where('activity_type', 'login')
            ->where('created_at', '>=', now()->subDays($days))
            ->count(),
    ];
}
```

**Advanced Access Control:**
```php
// Add to WorkspaceUser model
public function hasPermission(string $permission): bool
{
    // Check access level permissions
    if ($this->access_level === 'owner') return true;
    
    // Check role-based permissions
    return $this->user->hasWorkspacePermission($this->workspace, $permission);
}

public function canAccessResource(string $resource): bool
{
    $permissions = [
        'contracts' => 'contracts.view',
        'work_reports' => 'work_reports.view',
        'executors' => 'executors.view',
        'users' => 'users.view',
    ];
    
    return $this->hasPermission($permissions[$resource] ?? $resource . '.view');
}
```

### **9. Missing Critical Features to Implement**

**User Onboarding System:**
```php
// New UserOnboarding model
class UserOnboarding extends Model
{
    protected $fillable = [
        'workspace_user_id',
        'step',                    // Current onboarding step
        'completed_steps',         // JSON array of completed steps
        'assigned_mentor_id',      // Senior user assigned as mentor
        'onboarding_notes',        // Progress notes
        'completed_at',            // When onboarding was finished
    ];
    
    public function getOnboardingSteps(): array
    {
        return [
            'profile_setup' => 'Complete profile information',
            'role_training' => 'Complete role-specific training',
            'system_tour' => 'Take system tour',
            'first_task' => 'Complete first assigned task',
            'mentor_meeting' => 'Meet with assigned mentor',
        ];
    }
}
```

**User Performance Tracking:**
```php
// Add to WorkspaceUser
public function performanceMetrics(): HasOne
{
    return $this->hasOne(UserPerformanceMetric::class);
}

public function calculatePerformanceScore(): float
{
    return [
        'work_reports_timeliness' => $this->getWorkReportTimeliness(),
        'task_completion_rate' => $this->getTaskCompletionRate(),
        'quality_score' => $this->getQualityScore(),
        'safety_compliance' => $this->getSafetyComplianceScore(),
    ];
}
```

### **10. Romanian Construction Industry Specific Features**

**Professional Certification Tracking:**
```php
// Add to WorkspaceUser
protected $fillable = [
    // ... existing
    'professional_license',      // Romanian construction professional license
    'certifications',           // JSON array of industry certifications
    'training_records',         // JSON array of safety/technical training
    'background_check_date',    // Required for construction sites
    'medical_clearance_date',   // Health clearance for construction work
    'safety_training_expires',  // Safety certification expiration
];

protected $casts = [
    // ... existing
    'certifications' => 'array',
    'training_records' => 'array',
    'background_check_date' => 'date',
    'medical_clearance_date' => 'date',
    'safety_training_expires' => 'date',
];
```

## **Priority Implementation Roadmap**

### **Phase 1 (Week 1): Status System & Enhanced Model**
1. Create WorkspaceUserStatus enum
2. Add missing fields to WorkspaceUser model (status, job_title, department, etc.)
3. Implement status workflow methods
4. Update existing WorkspaceUser records with default statuses

### **Phase 2 (Week 2): Professional Form & Table Enhancement**
1. Enhanced WorkspaceUserForm with comprehensive user management
2. Advanced table with status badges, role display, and filtering
3. Professional action buttons and bulk operations
4. User activity tracking integration

### **Phase 3 (Week 3): Unified Invitation System**
1. Integrate WorkspaceInvitation data into WorkspaceUser workflow
2. Unified creation process for new vs. existing users
3. Enhanced email notifications and invitation management
4. Role assignment automation post-acceptance

### **Phase 4 (Week 4): Advanced Features & Romanian Compliance**
1. User onboarding system and progress tracking
2. Performance metrics and activity analytics
3. Professional certification and compliance tracking
4. Mobile worker management and field access optimization

## **Expected Business Impact**

- **Streamlined User Management**: Single interface for all workspace user operations
- **Professional Onboarding**: Comprehensive invitation and approval workflow
- **Enhanced Security**: Granular role-based permissions with workspace isolation
- **Compliance Ready**: Romanian construction industry professional requirements
- **Operational Efficiency**: Automated workflows reduce administrative overhead
- **Better User Experience**: Clear status tracking and intuitive management interface

## **Critical Missing Features to Address**

1. **Status Workflow Integration** - WorkspaceUser model lacks proper status management
2. **Unified User Interface** - Split between WorkspaceUsers and WorkspaceInvitations resources
3. **Romanian Professional Requirements** - Missing construction industry compliance fields
4. **Performance Tracking** - No user activity or performance monitoring
5. **Mobile Worker Management** - Missing field worker specific features and access control
6. **Bulk Operations** - Limited bulk user management capabilities

## **Integration with Existing Systems**

**Contract Integration:**
- Automatically assign contract permissions based on user roles
- Track user involvement in specific contracts
- Permission inheritance from workspace to contract level

**Work Report Integration:**
- Role-based work report approval workflows
- Field worker mobile access for report submission
- Supervisor approval and review capabilities

**Company Employee Integration:**
- Sync job titles and departments with CompanyEmployee records
- Integrate salary and employment data for comprehensive user profiles
- Track user employment history across multiple companies