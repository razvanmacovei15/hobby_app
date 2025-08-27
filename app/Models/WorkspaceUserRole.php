<?php

namespace App\Models;

use App\Models\Permission\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class WorkspaceUserRole extends Model
{
    protected $table = 'model_has_roles';
    
    protected $fillable = [
        'model_id',
        'role_id',
        'model_type',
    ];

    public $incrementing = false;
    
    protected $primaryKey = 'model_id';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->model_type) {
                $model->model_type = User::class;
            }
        });
    }

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'model_id';
    }
    
    public function getKeyName()
    {
        return 'model_id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'model_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Scope to only user models (not other model types)
     */
    public function scopeUsers(Builder $query): Builder
    {
        return $query->where('model_type', User::class);
    }

    /**
     * Scope to specific workspace
     */
    public function scopeForWorkspace(Builder $query, Workspace $workspace): Builder
    {
        return $query->whereHas('role', function ($roleQuery) use ($workspace) {
            $roleQuery->where('workspace_id', $workspace->id);
        });
    }

    /**
     * Get workspace through role
     */
    public function getWorkspaceAttribute()
    {
        return $this->role?->workspace;
    }
}