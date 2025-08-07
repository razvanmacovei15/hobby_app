<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'j',
        'cui',
        'place_of_registration',
        'iban',
        'address_id',
        'representative_id',
        'phone'
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function representative(): BelongsTo
    {
        return $this->belongsTo(User::class, 'representative_id');
    }

    public function beneficiaryContracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'beneficiary_id');
    }

    public function executorContracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'executor_id');
    }

    public function extraServices(): HasMany
    {
        return $this->hasMany(ContractExtraService::class);
    }
}
