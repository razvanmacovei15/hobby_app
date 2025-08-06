<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractService extends Model
{
    /** @use HasFactory<\Database\Factories\ContractServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_annex_id',
        'order',
        'name',
        'unit_of_measure',
        'price_per_unit_of_measure',
    ];

    public function contractAnnex(): BelongsTo
    {
        return $this->belongsTo(ContractAnnex::class);
    }
}
