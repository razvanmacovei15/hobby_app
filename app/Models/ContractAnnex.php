<?php

namespace App\Models;

use InvalidArgumentException;
use Database\Factories\ContractAnnexFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ContractAnnex extends Model
{
    /** @use HasFactory<ContractAnnexFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_id',

        'annex_number',
        'sign_date',
        'notes',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ContractedService::class, 'contract_annex_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (ContractAnnex $annex) {
            // Safety: if it's being created via relationship ($contract->annexes()->create()),
            // contract_id will already be set.
            if (! $annex->contract_id) {
                throw new InvalidArgumentException('contract_id is required to generate annex_number.');
            }

            // Robust version with row-level lock to avoid race conditions under high concurrency:
            DB::transaction(function () use ($annex) {
                $max = static::where('contract_id', $annex->contract_id)
                    ->lockForUpdate()   // SELECT ... FOR UPDATE
                    ->max('annex_number');

                $annex->annex_number = ($max ?? 0) + 1;
            });
        });
    }

    public function getFilamentName(): string
    {
        return "Annex nr. " . $this->annex_number . ' - Contract ' . $this->contract->registration_key;
    }
}
