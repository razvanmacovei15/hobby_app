<?php

namespace App\Models;

use Database\Factories\ContractServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ContractService extends Model
{
    /** @use HasFactory<ContractServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_annex_id',
        // maybe add a unique code for each service
        'sort_order',
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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (ContractService $contractService) {
            // Safety: if it's being created via relationship ($contract->annexes()->create()),
            // contract_id will already be set.
            if (! $contractService->contract_annex_id) {
                throw new InvalidArgumentException('contract_annex_id is required to generate annex_number.');
            }

            // Robust version with row-level lock to avoid race conditions under high concurrency:
            DB::transaction(function () use ($contractService) {
                $max = static::where('contract_annex_id', $contractService->contract_annex_id)
                    ->lockForUpdate()   // SELECT ... FOR UPDATE
                    ->max('sort_order');

                $contractService->sort_order = ($max ?? 0) + 1;
            });
        });
    }


}
