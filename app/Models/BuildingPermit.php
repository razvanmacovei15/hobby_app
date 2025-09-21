<?php

namespace App\Models;

use App\Enums\PermitType;
use App\Enums\PermitStatus;
use Database\Factories\BuildingPermitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuildingPermit extends Model
{
    /** @use HasFactory<BuildingPermitFactory> */
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'permit_type',
        'status',
        'workspace_id',
        'name',
        'height_regime',
        'land_book_number',
        'cadastral_number',
        'architect',
        'execution_duration_days',
        'image_url',
        'validity_term',
        'work_start_date',
        'work_end_date',
        'address_id',
        'issuance_year',
    ];

    protected $casts = [
        'permit_type' => PermitType::class,
        'status' => PermitStatus::class,
        'validity_term' => 'date',
        'work_start_date' => 'date',
        'work_end_date' => 'date',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->permit_number . '/' . $this->issuance_year;
    }

    public function getWorkEndDateAttribute($value)
    {
        if ($this->work_start_date && $this->execution_duration_days) {
            return $this->work_start_date->addDays($this->execution_duration_days);
        }
        return $value;
    }
}