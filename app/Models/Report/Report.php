<?php

namespace App\Models\Report;

use App\Models\Employee\EmployeeUnitAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reports';

    protected $fillable = [
        'tracking_code',
        'rating_id',
        'unit_id',
        'student_id',
        'report_category_id',
        'title',
        'description',
        'priority',
        'status',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function rating(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Feedback\Rating::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Unit\Unit::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Authentication\Student::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Report\ReportCategory::class, 'report_category_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(\App\Models\Report\ReportAttachment::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(\App\Models\Report\ReportReply::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(\App\Models\Report\ReportStatusHistory::class);
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(EmployeeUnitAssignment::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Report\ReportFactory::new();
    }
}
