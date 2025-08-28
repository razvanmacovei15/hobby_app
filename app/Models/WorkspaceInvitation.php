<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WorkspaceInvitation extends Model
{
//    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'invitee_id',
        'invitee_type',
        'invited_by',
        'token',
        'roles',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'roles' => 'array',
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(64);
            }
            if (empty($invitation->expires_at)) {
                $invitation->expires_at = Carbon::now()->addDays(2);
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function invitee(): MorphTo
    {
        return $this->morphTo();
    }

    // Convenience method for when we know it's a user invitation
    public function user(): ?User
    {
        return $this->invitee_type === User::class ? $this->invitee : null;
    }

    // Convenience method for when we know it's a company invitation
    public function company(): ?Company
    {
        return $this->invitee_type === Company::class ? $this->invitee : null;
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return !is_null($this->accepted_at);
    }

    public function isPending(): bool
    {
        return !$this->isAccepted() && !$this->isExpired();
    }

    public function accept(): void
    {
        $this->update(['accepted_at' => Carbon::now()]);
    }

    public function getInvitationUrl(): string
    {
        return route('workspace.invitation.accept', $this->token);
    }

    public function isUserInvitation(): bool
    {
        return $this->invitee_type === User::class;
    }

    public function isCompanyInvitation(): bool
    {
        return $this->invitee_type === Company::class;
    }

    public static function findByToken(string $token): ?self
    {
        return static::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('accepted_at')
            ->first();
    }

    // Helper to create user invitation
    public static function createForUser(User $user, Workspace $workspace, User $invitedBy, array $roles): self
    {
        return static::create([
            'workspace_id' => $workspace->id,
            'invitee_id' => $user->id,
            'invitee_type' => User::class,
            'invited_by' => $invitedBy->id,
            'roles' => $roles,
        ]);
    }

    // Helper to create company invitation (for future use)
    public static function createForCompany(Company $company, Workspace $workspace, User $invitedBy, array $roles): self
    {
        return static::create([
            'workspace_id' => $workspace->id,
            'invitee_id' => $company->id,
            'invitee_type' => Company::class,
            'invited_by' => $invitedBy->id,
            'roles' => $roles,
        ]);
    }
}
