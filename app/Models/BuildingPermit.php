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
    ];

    protected $casts = [
        'permit_type' => PermitType::class,
        'status' => PermitStatus::class,
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }
}