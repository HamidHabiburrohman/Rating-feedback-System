<?php

namespace App\Models\Employee;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeUnitAssignment extends Model
{
    use HasFactory;

    protected $table = 'employee_unit_assignments';

    protected $fillable = [
        'employee_id',
        'unit_id',
        'assigned_by_admin_id',
        'assigned_at',
        'ended_at',
        'is_active',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\EmployeeUnitAssignmentFactory::new();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function assignedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_by_admin_id');
    }
}