<?php

namespace App\Models;

use Database\Factories\WorkspaceExecutorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceExecutor extends Model
{
    /** @use HasFactory<WorkspaceExecutorFactory> */
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'executor_id',

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
}
