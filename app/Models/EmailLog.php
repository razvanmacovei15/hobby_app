<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'workspace_invitation_id',
        'recipient_email',
        'recipient_name',
        'sender_email',
        'sender_name',
        'subject',
        'status', // pending, sent, failed, bounced, delivered, opened, clicked
        'sent_at',
        'delivered_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'error_message',
        'ses_message_id',
        'metadata'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'metadata' => 'array'
    ];

    public function workspaceInvitation(): BelongsTo
    {
        return $this->belongsTo(WorkspaceInvitation::class);
    }

    public function isDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'opened', 'clicked']);
    }

    public function isFailed(): bool
    {
        return in_array($this->status, ['failed', 'bounced']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function scopeForWorkspace($query, $workspaceId)
    {
        return $query->whereHas('workspaceInvitation', function($q) use ($workspaceId) {
            $q->where('workspace_id', $workspaceId);
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('sent_at', '>=', now()->subDays($days));
    }
}
