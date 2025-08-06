<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function constructionSites(){
        return $this->hasMany(ConstructionSite::class);
    }

    public function address(){
        return $this->hasOne(Address::class, 'address_id');
    }

}
