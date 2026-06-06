<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reports';

    protected $fillable = [
        'tracking_code', 'rating_id', 'unit_id', 'student_id', 'report_category_id',
        'title', 'description', 'priority', 'status', 'assigned_to_employee_id',
        'admin_id', 'admin_response', 'replied_at', 'resolved_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'resolved_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function rating()
    {
        return $this->belongsTo(\App\Models\Feedback\Rating::class);
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit\Unit::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\Authentication\Student::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Report\ReportCategory::class, 'report_category_id');
    }

    public function assignedToEmployee()
    {
        return $this->belongsTo(\App\Models\Authentication\Employee::class, 'assigned_to_employee_id');
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class);
    }

    public function attachments()
    {
        return $this->hasMany(\App\Models\Report\ReportAttachment::class);
    }

    public function replies()
    {
        return $this->hasMany(\App\Models\Report\ReportReply::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(\App\Models\Report\ReportStatusHistory::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ReportFactory::new();
    }
}