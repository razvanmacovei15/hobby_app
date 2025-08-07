<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'city',
        'street',
        'street_number',
        'building',
        'apartment_number',
        'state',
        'country',
    ];

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function constructionSite(): HasOne
    {
        return $this->hasOne(ConstructionSite::class);
    }

    public function building(): HasOne
    {
        return $this->hasOne(Building::class);
    }

    public function apartment(): HasOne
    {
        return $this->hasOne(Apartment::class);
    }
}
