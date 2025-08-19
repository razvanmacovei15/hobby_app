<?php

namespace App\Models;

use Database\Factories\WorkReportExtraServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WorkReportExtraService extends Model
{
    /** @use HasFactory<WorkReportExtraServiceFactory> */
    use HasFactory;
    protected $fillable = [
        'work_report_id',
        'contract_id',
        'executor_company_id',
        'beneficiary_company_id',

        'name',
        'unit_of_measure',
        'price_per_unit_of_measure',
        'notes',
    ];

    public function workReport(): BelongsTo
    {
        return $this->belongsTo(WorkReport::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function executorCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'executor_company_id');
    }

    public function beneficiaryCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'beneficiary_company_id');
    }

    public function workReportEntry(): MorphMany
    {
        return $this->morphMany(WorkReportEntry::class, 'service');
    }
}
