<?php

namespace App\Models\Permission;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\Workspace;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'workspace_id',
        'display_name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (!$role->workspace_id && Filament::getTenant()) {
                $role->workspace_id = Filament::getTenant()->id;
            }
            
            if (!$role->guard_name) {
                $role->guard_name = 'web';
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Scope roles to a specific workspace
     */
    public function scopeForWorkspace($query, Workspace $workspace)
    {
        return $query->where('workspace_id', $workspace->id);
    }

    /**
     * Check if this is a global role (no workspace)
     */
    public function isGlobal(): bool
    {
        return $this->workspace_id === null;
    }

    /**
     * Get workspace-specific roles for a workspace
     */
    public static function forWorkspace(Workspace $workspace)
    {
        return static::where('workspace_id', $workspace->id)->get();
    }
}