<?php

namespace App\Models;

use InvalidArgumentException;
use DB;
use Database\Factories\WorkReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkReport extends Model
{
    /** @use HasFactory<WorkReportFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'beneficiary_id',
        'executor_id',

        'written_by',
        'report_month', // e.g., 5
        'report_year', // e.g., 2025
        'report_number', //incrementing for all reports
        'notes', // optional notes or remarks

        // add status later draft|submitted|approved|locked
        // add approved_at and approved_by
        // add locked_at
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'beneficiary_id');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'executor_id');
    }

    public function writtenBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'written_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(WorkReportEntry::class);
    }

    public function extraServices(): HasMany
    {
        return $this->hasMany(WorkReportExtraService::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (WorkReport $wr) {
            if (!$wr->contract_id) {
                throw new InvalidArgumentException('contract_id required.');
            }

            DB::transaction(function () use ($wr) {
                $max = WorkReport::where('contract_id', $wr->contract_id)
                    ->where('report_year', $wr->report_year)
                    ->lockForUpdate()
                    ->max('report_number');

                $wr->report_number = ($max ?? 0) + 1;
            });
        });
    }
}
