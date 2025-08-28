<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyEmployee extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyEmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'job_title',
        'salary',
        'hired_at',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'salary' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function getTitleAttribute(): string
    {
        return $this->user?->getFilamentName() ?? 'Unknown Employee';
    }
}
