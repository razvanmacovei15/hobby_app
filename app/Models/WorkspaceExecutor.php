<?php

namespace App\Models;

use Database\Factories\WorkspaceExecutorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkspaceExecutor extends Model
{
    /** @use HasFactory<WorkspaceExecutorFactory> */
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'executor_id',
        'responsible_engineer_id',

        'is_active',
        'executor_type',
        'has_contract',
    ];

    public function executor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'executor_id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function responsibleEngineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_engineer_id');
    }

    public function responsibleEngineers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_executor_engineers')
            ->using(WorkspaceExecutorEngineer::class)
            ->withPivot(['role', 'assigned_at'])
            ->withTimestamps();
    }

    public function getPrimaryEngineer(): ?User
    {
        return $this->responsibleEngineers()
            ->wherePivot('role', 'primary')
            ->first();
    }

    public function addEngineer(User $user, string $role = 'engineer'): void
    {
        $this->responsibleEngineers()->attach($user->id, [
            'role' => $role,
            'assigned_at' => now(),
        ]);
    }

    public function removeEngineer(User $user): void
    {
        $this->responsibleEngineers()->detach($user->id);
    }

    public function syncEngineers(array $engineerData): void
    {
        $this->responsibleEngineers()->sync($engineerData);
    }

    public function getFilamentName(): string
    {
        return $this->executor?->name ?? 'Unknown Executor';
    }
}
