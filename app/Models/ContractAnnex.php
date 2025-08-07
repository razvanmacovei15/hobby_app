<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractAnnex extends Model
{
    /** @use HasFactory<\Database\Factories\ContractAnnexFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'annex_number',
        'sign_date',
        'description',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ContractService::class);
    }
}
