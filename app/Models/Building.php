<?php

namespace App\Models;

use App\Enums\BuildingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Building extends Model
{
    /** @use HasFactory<\Database\Factories\BuildingFactory> */
    use HasFactory;

    protected $fillable = [
        'name',

        'building_type', // enum type

        'address_id',
        'construction_site_id'
    ];

    protected $casts = [
        'building_type' => BuildingType::class,
    ];

    public function constructionSite(): BelongsTo
    {
        return $this->belongsTo(ConstructionSite::class, 'construction_site_id');
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'address_id');
    }

    public function staircases(): HasMany
    {
        return $this->hasMany(Staircase::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }

}
