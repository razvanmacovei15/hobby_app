<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class WorkReport extends Model
{
    /** @use HasFactory<\Database\Factories\WorkReportFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_id',

        'written_by',
        'report_month', // e.g., july
        'report_year', // e.g., 2025
        'report_number', //incrementing for all reports
        'observations', // optional notes or remarks
    ];

    // need some through methods here to get the executor from the contract_id and all the services from the contract annex in the contract with the contract_id

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function writtenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'written_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(WorkReportEntry::class);
    }

    public function executor(): HasOneThrough
    {
        return $this->hasOneThrough(Company::class, Contract::class, 'id', 'id', 'contract_id', 'executor_id');
    }

    public function beneficiary(): HasOneThrough
    {
        return $this->hasOneThrough(Company::class, Contract::class, 'id', 'id', 'contract_id', 'beneficiary_id');
    }

    public function contractServices(): HasManyThrough
    {
        return $this->hasManyThrough(ContractService::class, ContractAnnex::class, 'contract_id', 'contract_annex_id', 'contract_id', 'id');
    }

    public function contractExtraServices(): HasManyThrough
    {
        return $this->hasManyThrough(ContractExtraService::class, Contract::class, 'id', 'contract_id', 'contract_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workReport) {
            // Get the highest report number for the current year and increment it
            $lastReport = static::where('report_year', $workReport->report_year)
                ->orderBy('report_number', 'desc')
                ->first();
            $workReport->report_number = $lastReport ? $lastReport->report_number + 1 : 1;
        });
    }
}
