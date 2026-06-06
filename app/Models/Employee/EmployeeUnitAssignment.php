<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeUnitAssignment extends Model
{
    use HasFactory;

    protected $table = 'employee_unit_assignments';

    protected $fillable = [
        'employee_id', 'unit_id', 'assigned_by_admin_id',
        'role_in_unit', 'assigned_at', 'ended_at', 'is_active'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\Authentication\Employee::class);
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit\Unit::class);
    }

    public function assignedByAdmin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class, 'assigned_by_admin_id');
    }
}