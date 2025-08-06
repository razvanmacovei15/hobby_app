<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'beneficiary_id',
        'executor_id',
        'start_date',
        'end_date',
        'sign_date',
    ];

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'beneficiary_id');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'executor_id');
    }

    public function annexes(): HasMany
    {
        return $this->hasMany(ContractAnnex::class);
    }
}
