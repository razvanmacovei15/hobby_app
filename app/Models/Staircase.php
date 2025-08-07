<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staircase extends Model
{
    /** @use HasFactory<\Database\Factories\StaircaseFactory> */
    use HasFactory;

    protected $fillable = [
        'label',

        'building_id'
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }
}
