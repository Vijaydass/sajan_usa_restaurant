<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_metric_id',
        'sale',
        'growth',
        'speed_service',
        'complaints',
        'osat',
        'redbook',
    ];

    /**
     * Relationship: A Performance belongs to a WeeklyMetric.
     */
    public function weeklyMetric()
    {
        return $this->belongsTo(WeeklyMetric::class);
    }
}
