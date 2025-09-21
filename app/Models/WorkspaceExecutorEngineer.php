<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WorkspaceExecutorEngineer extends Pivot
{
    protected $fillable = [
        'workspace_executor_id',
        'user_id',
        'role',
        'assigned_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    public function workspaceExecutor(): BelongsTo
    {
        return $this->belongsTo(WorkspaceExecutor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function engineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
