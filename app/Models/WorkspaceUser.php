<?php

namespace App\Models;

use Database\Factories\WorkspaceUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkspaceUser extends Model
{
    /** @use HasFactory<WorkspaceUserFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'workspace_id',
        'is_default',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
