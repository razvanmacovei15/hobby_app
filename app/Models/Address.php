<?php

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
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

    // Optional: expose it automatically when the model is JSON-cast
    protected $appends = ['full_address'];

    public function getFullAddressAttribute(): string
    {
        $line1 = array_filter([
            trim(implode(' ', array_filter([$this->street, $this->street_number])) ?: ''),
            $this->building ? 'Bl. '.$this->building : null,
            $this->apartment_number ? 'Ap. '.$this->apartment_number : null,
        ]);

        $line2 = array_filter([
            $this->city,
            $this->state,
            $this->country,
        ]);

        $fullAddress = trim(implode(', ', array_filter([
            implode(', ', $line1) ?: null,
            implode(', ', $line2) ?: null,
        ])));

        return $fullAddress ?: 'No Address';
    }

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
