<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Floor extends Model
{
    /** @use HasFactory<\Database\Factories\FloorFactory> */
    use HasFactory;

    protected $fillable = [
        'name',

        'building_id',
        'staircase_id'
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function staircase(): BelongsTo
    {
        return $this->belongsTo(Staircase::class, 'staircase_id');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }
}
