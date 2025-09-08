<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasName, FilamentUser, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_users', 'user_id', 'workspace_id')
            ->withTimestamps();
    }


    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->workspaces()->whereKey($tenant->getKey())->exists();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->workspaces()->get();
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        // Get the default workspace for this user
        return $this->workspaces()
            ->wherePivot('is_default', true)
            ->first();
    }

    /**
     * Get user's roles in a specific workspace
     */
    public function getWorkspaceRoles(Workspace $workspace)
    {
        return $this->roles()->where('workspace_id', $workspace->id)->get();
    }

    /**
     * Check if user has a specific role in a workspace
     */
    public function hasWorkspaceRole(Workspace $workspace, string $roleName): bool
    {
        return $this->roles()
            ->where('workspace_id', $workspace->id)
            ->where('name', $roleName)
            ->exists();
    }

    /**
     * Check if user has a specific permission in a workspace
     */
    public function hasWorkspacePermission(Workspace $workspace, string $permission): bool
    {
        // Check direct permissions
        if ($this->permissions()->where('workspace_id', $workspace->id)->where('name', $permission)->exists()) {
            return true;
        }

        // Check permissions through roles
        $workspaceRoles = $this->getWorkspaceRoles($workspace);
        foreach ($workspaceRoles as $role) {
            if ($role->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assign a role to user in a specific workspace
     */
    public function assignWorkspaceRole(Workspace $workspace, string $roleName): void
    {
        $role = \App\Models\Permission\Role::where('name', $roleName)
            ->where('workspace_id', $workspace->id)
            ->first();

        if ($role) {
            $this->assignRole($role);
        }
    }

    /**
     * Get companies where this user is employed
     */
    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_employees')
            ->using(CompanyEmployee::class)
            ->withPivot(['job_title', 'salary', 'hired_at'])
            ->withTimestamps();
    }

    /**
     * Workspace invitations received by this user (polymorphic)
     */
    public function workspaceInvitations(): MorphMany
    {
        return $this->morphMany(WorkspaceInvitation::class, 'invitee');
    }

    /**
     * Workspace invitations sent by this user (as admin)
     */
    public function sentWorkspaceInvitations(): HasMany
    {
        return $this->hasMany(WorkspaceInvitation::class, 'invited_by');
    }

    /**
     * Get pending workspace invitations for this user
     */
    public function pendingWorkspaceInvitations(): MorphMany
    {
        return $this->workspaceInvitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now());
    }

    /**
     * Check if user has pending invitation to a workspace
     */
    public function hasPendingInvitationTo(Workspace $workspace): bool
    {
        return $this->pendingWorkspaceInvitations()
            ->where('workspace_id', $workspace->id)
            ->exists();
    }

    /**
     * Get the current workspace for this user
     */
    public function getCurrentWorkspaceAttribute(): Workspace
    {
        $currentTenant = Filament::getTenant();
        
        if (!$currentTenant instanceof Workspace) {
            throw new \RuntimeException('No workspace context available. User must be operating within a workspace.');
        }

        return $currentTenant;
    }
}
