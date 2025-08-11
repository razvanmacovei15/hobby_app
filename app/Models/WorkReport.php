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
        'company_id',
        'written_by',
        'report_month', // e.g., july
        'report_year', // e.g., 2025
        'report_number', //incrementing for all reports
        'observations', // optional notes or remarks
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function writtenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'written_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(WorkReportEntry::class);
    }

    // Get contracts where this company is the executor
    public function contractsAsExecutor(): HasMany
    {
        return $this->hasMany(Contract::class, 'executor_id', 'company_id');
    }

    // Get contracts where this company is the beneficiary
    public function contractsAsBeneficiary(): HasMany
    {
        return $this->hasMany(Contract::class, 'beneficiary_id', 'company_id');
    }

    // Get all contracts for this company (either as executor or beneficiary)
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'executor_id', 'company_id');
    }

    // Get contract services through contracts where this company is the executor
    public function contractServices(): HasManyThrough
    {
        return $this->hasManyThrough(
            ContractService::class,
            ContractAnnex::class,
            'contract_id', // Foreign key on contract_annexes table
            'contract_annex_id', // Foreign key on contract_services table
            'company_id', // Local key on work_reports table
            'id' // Local key on contract_annexes table
        )->whereHas('contract', function ($query) {
            $query->where('executor_id', $this->company_id);
        });
    }

    // Get contract extra services through contracts where this company is the executor
    public function contractExtraServices(): HasManyThrough
    {
        return $this->hasManyThrough(
            ContractExtraService::class,
            Contract::class,
            'executor_id', // Foreign key on contracts table
            'contract_id', // Foreign key on contract_extra_services table
            'company_id', // Local key on work_reports table
            'id' // Local key on contracts table
        );
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
