<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
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
        return $this->hasMany(WorkReportExtraService::class, 'executor_company_id');
    }

    public function beneficiaryExtraServices(): HasMany
    {
        return $this->hasMany(WorkReportExtraService::class, 'beneficiary_company_id');
    }

    public function ownedWorkspaces()
    {
        return $this->hasMany(Workspace::class, 'owner_id');
    }

    public function asExecutorIn()
    {
        return $this->belongsToMany(Workspace::class,'workspace_executors')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }
}
