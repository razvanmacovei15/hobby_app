<?php

namespace App\Models;

use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        return $this
            ->belongsToMany(
                related: Company::class,
                table: 'workspace_executors',
                foreignPivotKey: 'workspace_id',
                relatedPivotKey: 'executor_id',
            )
            ->withPivot(['is_active', 'has_contract'])
            ->withTimestamps()
            ->wherePivot('is_active', true)
            ->wherePivot('has_contract', true);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'workspace_users', 'workspace_id', 'user_id')
            ->withTimestamps();
    }

    public function buildingPermit(): HasOne
    {
        return $this->hasOne(BuildingPermit::class);
    }
}
