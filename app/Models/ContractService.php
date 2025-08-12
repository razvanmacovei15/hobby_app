<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ContractService extends Model
{
    /** @use HasFactory<\Database\Factories\ContractServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_annex_id',
        // maybe add a unique code for each service
        'order',
        'name',
        'unit_of_measure',
        'price_per_unit_of_measure',
    ];

    public function contractAnnex(): BelongsTo
    {
        return $this->belongsTo(ContractAnnex::class);
    }

    public function contract(): HasOneThrough
    {
        return $this->hasOneThrough(
            Contract::class,
            ContractAnnex::class,
            'id',            // ContractAnnex.id
            'id',            // Contract.id
            'contract_annex_id', // ContractService.contract_annex_id
            'contract_id'        // ContractAnnex.contract_id
        );
    }


    public function workReportEntries(): MorphMany
    {
        return $this->morphMany(WorkReportEntry::class, 'service');
    }


}
