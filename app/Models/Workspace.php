<?php

namespace App\Models;

use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    /** @use HasFactory<WorkspaceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',

        'owner_id'
    ];

    public function ownerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'owner_id');
    }

    public function executors(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'workspace_executors')
            ->withPivot(['is_active'])
            ->withTimestamps()
            ->wherePivot('is_active', true);
    }

    public function contracts()
    {
        return $this->belongsToMany(Contract::class, 'workspace_contracts')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'workspace_users', 'workspace_id', 'user_id')
            ->withTimestamps();
    }
}
