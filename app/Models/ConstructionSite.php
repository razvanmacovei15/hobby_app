<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionSite extends Model
{
    /** @use HasFactory<\Database\Factories\ConstructionSiteFactory> */
    use HasFactory;

    protected $fillable = [
        'name',

        'location_id',
        'site_director_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function siteDirector(){
        return $this->hasOne(User::class, 'site_director_id');
    }

}
