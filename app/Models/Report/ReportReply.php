<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'report_replies';

    protected $fillable = ['report_id', 'employee_id', 'admin_id', 'reply', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(\App\Models\Report\Report::class);
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\Authentication\Employee::class);
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Report\ReportReplyFactory::new();
    }
}