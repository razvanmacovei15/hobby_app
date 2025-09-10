# Workspace Users Implementation Plan

## Overview
Replace the current Users resource with a dedicated WorkspaceUsers resource that manages the `workspace_users` table directly. This will provide a unified view of all workspace members with their invitation status, roles, and user information in one place.

## Current State Analysis
- Users resource manages direct User model
- User-workspace relationship via `workspace_users` pivot table
- WorkspaceInvitation system exists but is disconnected from user management
- No unified view of workspace membership status

## Target Implementation
- New WorkspaceUsers resource managing `workspace_users` table directly  
- WorkspaceUser model with invitation status tracking
- Custom invitation logic integrated into creation flow
- Unified workspace member management interface

---

## PHASE 1: Database & Model Foundation

### 1.1 Create WorkspaceUser Model & Migration
```bash
php artisan make:model WorkspaceUser -m
```

**Migration Structure:**
```php
Schema::create('workspace_users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('workspace_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->enum('status', ['pending', 'approved', 'expired', 'declined'])->default('pending');
    $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null');
    $table->string('invitation_token', 64)->nullable()->unique();
    $table->timestamp('invited_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamp('accepted_at')->nullable();
    $table->boolean('is_default')->default(false);
    $table->timestamps();

    $table->unique(['workspace_id', 'user_id']);
    $table->index(['invitation_token', 'expires_at']);
});
```

### 1.2 Create WorkspaceUserStatus Enum
```php
// app/Enums/WorkspaceUserStatus.php
enum WorkspaceUserStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case EXPIRED = 'expired';
    case DECLINED = 'declined';
}
```

### 1.3 WorkspaceUser Model Implementation
```php
class WorkspaceUser extends Model
{
    protected $fillable = [
        'workspace_id', 'user_id', 'status', 'invited_by',
        'invitation_token', 'invited_at', 'expires_at', 'accepted_at', 'is_default'
    ];

    protected $casts = [
        'status' => WorkspaceUserStatus::class,
        'invited_at' => 'datetime',
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function workspace(): BelongsTo
    public function user(): BelongsTo  
    public function invitedBy(): BelongsTo
    public function roles(): BelongsToMany // via Spatie permissions

    // Status methods
    public function isPending(): bool
    public function isExpired(): bool
    public function isApproved(): bool
    
    // Invitation methods
    public function generateInvitationToken(): void
    public function sendInvitationEmail(): void
    public function acceptInvitation(): void
    public function expireInvitation(): void
}
```

### 1.4 Factory & Seeder
- **WorkspaceUserFactory**: Generate test data with various statuses
- **WorkspaceUserSeeder**: Create realistic workspace membership scenarios

---

## PHASE 2: Update Existing Models

### 2.1 Update User Model
```php
// Replace workspace relationship
public function workspaceUsers(): HasMany
{
    return $this->hasMany(WorkspaceUser::class);
}

public function workspaces(): BelongsToMany
{
    return $this->belongsToMany(Workspace::class, 'workspace_users')
        ->using(WorkspaceUser::class)
        ->withPivot(['status', 'invited_by', 'invitation_token', 'invited_at', 'expires_at', 'accepted_at', 'is_default'])
        ->withTimestamps();
}

// Helper methods
public function getWorkspaceUser(Workspace $workspace): ?WorkspaceUser
public function hasWorkspaceAccess(Workspace $workspace): bool
public function getWorkspaceStatus(Workspace $workspace): ?WorkspaceUserStatus
```

### 2.2 Update Workspace Model
```php
public function workspaceUsers(): HasMany
{
    return $this->hasMany(WorkspaceUser::class);
}

public function approvedUsers(): HasMany
{
    return $this->workspaceUsers()->where('status', WorkspaceUserStatus::APPROVED);
}

public function pendingUsers(): HasMany  
{
    return $this->workspaceUsers()->where('status', WorkspaceUserStatus::PENDING);
}
```

---

## PHASE 3: Filament Resource Implementation

### 3.1 Create WorkspaceUsers Resource
```bash
php artisan make:filament-resource WorkspaceUser --generate
```

**Resource Structure:**
```
app/Filament/Resources/WorkspaceUsers/
├── WorkspaceUserResource.php
├── Pages/
│   ├── ListWorkspaceUsers.php
│   ├── CreateWorkspaceUser.php (Custom invitation page)
│   ├── EditWorkspaceUser.php
│   └── ViewWorkspaceUser.php
├── Schemas/
│   ├── WorkspaceUserForm.php
│   ├── WorkspaceUserInfolist.php
│   └── WorkspaceUserTable.php
└── Actions/
    ├── SendInvitationAction.php
    └── ResendInvitationAction.php
```

### 3.2 WorkspaceUserTable Implementation
**Columns to Display:**
- User avatar + name (from User model)
- Email (from User model)  
- Job title (from CompanyEmployee relationship)
- Roles (workspace-specific roles as badges)
- Status (pending/approved/expired/declined with color coding)
- Invited by (admin name)
- Invited date
- Expires date (for pending invitations)

**Query Implementation:**
```php
public static function getEloquentQuery(): Builder
{
    $workspace = Filament::getTenant();
    
    return parent::getEloquentQuery()
        ->with([
            'user', 
            'workspace', 
            'invitedBy',
            'user.roles' => function ($query) use ($workspace) {
                $query->where('workspace_id', $workspace->id);
            }
        ])
        ->where('workspace_id', $workspace->id);
}
```

### 3.3 Custom Invitation Form
**Form Fields:**
- Employee selector (from company employees not in workspace)
- Role multi-select (workspace-specific roles)
- Custom message (optional)
- Expiration period (default 2 days)

**Form Logic:**
```php
// Only show employees from current company who aren't in workspace
Select::make('user_id')
    ->options(function () {
        return CompanyEmployee::whereHas('user', function ($query) {
            $query->whereDoesntHave('workspaceUsers', function ($subQuery) {
                $subQuery->where('workspace_id', Filament::getTenant()->id)
                         ->where('status', '!=', WorkspaceUserStatus::DECLINED);
            });
        })->with('user')->get()
        ->pluck('user.full_name', 'user.id');
    })
```

---

## PHASE 4: Custom Creation Logic

### 4.1 CreateWorkspaceUser Page
```php
protected function handleRecordCreation(array $data): Model
{
    return DB::transaction(function () use ($data) {
        $workspace = Filament::getTenant();
        $invitedBy = auth()->user();
        
        // Create WorkspaceUser with pending status
        $workspaceUser = WorkspaceUser::create([
            'workspace_id' => $workspace->id,
            'user_id' => $data['user_id'],
            'status' => WorkspaceUserStatus::PENDING,
            'invited_by' => $invitedBy->id,
            'invited_at' => now(),
            'expires_at' => now()->addDays(2),
        ]);
        
        // Generate invitation token
        $workspaceUser->generateInvitationToken();
        
        // Store roles to assign after acceptance
        if (!empty($data['roles'])) {
            // Store in session or separate table for post-acceptance assignment
            cache()->put(
                "workspace_invitation_roles_{$workspaceUser->invitation_token}",
                $data['roles'],
                now()->addDays(2)
            );
        }
        
        // Send invitation email
        $workspaceUser->sendInvitationEmail($data['custom_message'] ?? null);
        
        return $workspaceUser;
    });
}
```

### 4.2 Email Notification System
```php
// app/Notifications/WorkspaceInvitation.php
class WorkspaceInvitation extends Notification
{
    public function __construct(
        public WorkspaceUser $workspaceUser,
        public ?string $customMessage = null
    ) {}
    
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Workspace Invitation - {$this->workspaceUser->workspace->name}")
            ->greeting("Hello {$notifiable->first_name}!")
            ->line("You've been invited to join {$this->workspaceUser->workspace->name}")
            ->when($this->customMessage, fn($mail) => $mail->line($this->customMessage))
            ->action('Accept Invitation', $this->getInvitationUrl())
            ->line('This invitation expires in 2 days.');
    }
    
    private function getInvitationUrl(): string
    {
        return route('workspace.invitation.accept', $this->workspaceUser->invitation_token);
    }
}
```

---

## PHASE 5: Invitation Acceptance System

### 5.1 Custom Invitation Route & Controller
```php
// routes/web.php
Route::get('/invitations/{token}', [WorkspaceInvitationController::class, 'show'])
    ->name('workspace.invitation.accept');
    
Route::post('/invitations/{token}', [WorkspaceInvitationController::class, 'accept'])
    ->name('workspace.invitation.process');
```

### 5.2 Invitation Acceptance Page
**Custom Blade Template with:**
- Pre-filled, disabled fields (email, name)
- Password creation fields
- Workspace information display
- Terms acceptance checkbox

### 5.3 Acceptance Logic
```php
public function accept(Request $request, string $token)
{
    $workspaceUser = WorkspaceUser::where('invitation_token', $token)
        ->where('status', WorkspaceUserStatus::PENDING)
        ->where('expires_at', '>', now())
        ->firstOrFail();
        
    DB::transaction(function () use ($workspaceUser, $request) {
        // Update user password
        $workspaceUser->user->update([
            'password' => Hash::make($request->password)
        ]);
        
        // Accept invitation
        $workspaceUser->update([
            'status' => WorkspaceUserStatus::APPROVED,
            'accepted_at' => now(),
        ]);
        
        // Assign roles from cache
        $roles = cache()->get("workspace_invitation_roles_{$workspaceUser->invitation_token}");
        if ($roles) {
            foreach ($roles as $roleId) {
                $role = Role::find($roleId);
                if ($role && $role->workspace_id === $workspaceUser->workspace_id) {
                    $workspaceUser->user->assignRole($role);
                }
            }
            cache()->forget("workspace_invitation_roles_{$workspaceUser->invitation_token}");
        }
    });
    
    return redirect()->route('filament.admin.pages.dashboard')
        ->with('success', 'Welcome to the workspace!');
}
```

---

## PHASE 6: Advanced Features

### 6.1 Status Management Actions
- **Resend Invitation**: Generate new token, extend expiration
- **Cancel Invitation**: Mark as declined
- **Remove User**: Soft delete or archive
- **Change Roles**: Update workspace-specific roles

### 6.2 Bulk Operations
- Bulk invite multiple employees
- Bulk role assignment
- Export workspace user list

### 6.3 Invitation Analytics
- Track invitation acceptance rates
- Show invitation history
- Monitor expired invitations

---

## PHASE 7: Migration from Current System

### 7.1 Data Migration Script
```php
// Convert existing workspace_users pivot data to WorkspaceUser records
$existingPivotData = DB::table('workspace_users')->get();

foreach ($existingPivotData as $pivot) {
    WorkspaceUser::create([
        'workspace_id' => $pivot->workspace_id,
        'user_id' => $pivot->user_id,
        'status' => WorkspaceUserStatus::APPROVED, // Existing users are approved
        'accepted_at' => $pivot->created_at,
        'is_default' => $pivot->is_default ?? false,
        'created_at' => $pivot->created_at,
        'updated_at' => $pivot->updated_at,
    ]);
}
```

### 7.2 Update Navigation
- Remove old Users resource from navigation
- Add WorkspaceUsers resource to appropriate group
- Update any references in other resources

---

## PHASE 8: Testing & Validation

### 8.1 Feature Tests
- Invitation creation and email sending
- Token validation and expiration
- User acceptance flow
- Role assignment after acceptance
- Status transitions

### 8.2 Integration Tests  
- Multi-workspace scenarios
- Permission inheritance
- Email delivery
- Database consistency

---

## PHASE 9: Documentation & Training

### 9.1 User Documentation
- How to invite team members
- Understanding invitation statuses
- Managing workspace roles

### 9.2 Technical Documentation
- Model relationships diagram
- API endpoints documentation
- Email template customization guide

---

## Implementation Timeline

**Week 1**: Phase 1-2 (Database & Models)
**Week 2**: Phase 3-4 (Filament Resource & Creation Logic)  
**Week 3**: Phase 5-6 (Invitation System & Advanced Features)
**Week 4**: Phase 7-9 (Migration, Testing, Documentation)

## Success Metrics

- ✅ Unified workspace user management
- ✅ Clear invitation status tracking
- ✅ Seamless user onboarding experience
- ✅ Reduced administrative overhead
- ✅ Better visibility into workspace membership

This implementation will provide a much more intuitive and powerful workspace user management system that aligns perfectly with your business workflow!