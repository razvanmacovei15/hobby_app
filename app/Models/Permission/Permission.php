<?php

namespace App\Models\Permission;

use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Models\Workspace;
use App\Enums\PermissionCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'workspace_id',
        'category',
        'description',
    ];

    protected $casts = [
        'category' => PermissionCategory::class,
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Scope permissions to a specific workspace
     */
    public function scopeForWorkspace($query, Workspace $workspace)
    {
        return $query->where('workspace_id', $workspace->id);
    }

    /**
     * Check if this is a global permission (no workspace)
     */
    public function isGlobal(): bool
    {
        return $this->workspace_id === null;
    }

    /**
     * Get workspace-specific permissions for a workspace
     */
    public static function forWorkspace(Workspace $workspace)
    {
        return static::where('workspace_id', $workspace->id)->get();
    }
}