<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'report_status_histories';

    protected $fillable = [
        'report_id', 'old_status', 'new_status',
        'changed_by_admin_id', 'changed_by_employee_id', 'reason'
    ];

    protected $casts = [
        'changed_by_admin_id' => 'integer',
        'changed_by_employee_id' => 'integer'
    ];

    public function report()
    {
        return $this->belongsTo(\App\Models\Report\Report::class);
    }

    public function changedByAdmin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class, 'changed_by_admin_id');
    }

    public function changedByEmployee()
    {
        return $this->belongsTo(\App\Models\Authentication\Employee::class, 'changed_by_employee_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\Report\ReportStatusHistoryFactory::new();
    }
}