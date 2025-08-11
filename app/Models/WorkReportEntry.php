<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkReportEntry extends Model
{
    /** @use HasFactory<\Database\Factories\WorkReportEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'work_report_id',
        'order',

        'service_type',
        'service_id',
        'quantity',
        'total'
    ];

    public function workReport(): BelongsTo
    {
        return $this->belongsTo(WorkReport::class);
    }

    public function service(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (WorkReportEntry $entry) {
            if (is_null($entry->order)) {
                $maxOrder = static::where('work_report_id', $entry->work_report_id)->max('order');
                $entry->order = ($maxOrder ?? 0) + 1;
            }
        });
    }

}
