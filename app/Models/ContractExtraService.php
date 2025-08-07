<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContractExtraService extends Model
{
    /** @use HasFactory<\Database\Factories\ContractExtraServiceFactory> */
    use HasFactory;
    protected $fillable = [
        'contract_id',
        'company_id',
        'name',
        'unit_of_measure',
        'price_per_unit_of_measure',
        'quantity',
        'description',
        'provided_at',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function workReportEntry(): MorphMany
    {
        return $this->morphMany(WorkReportEntry::class, 'service');
    }

}
