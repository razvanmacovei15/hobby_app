<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ConstructionSite extends Model
{
    /** @use HasFactory<\Database\Factories\ConstructionSiteFactory> */
    use HasFactory;

    protected $fillable = [
        'name',

        'location_id',
        'address_id',
        'site_director_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function siteDirector()
    {
        return $this->hasOne(User::class, 'site_director_id');
    }

    public function buildings()
    {
        return $this->hasMany(Building::class, 'building_id');
    }

}
