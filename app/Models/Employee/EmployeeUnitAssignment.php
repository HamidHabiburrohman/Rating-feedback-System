<?php

namespace App\Models\Employee;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Message\Message;
use App\Models\Report\Report;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeUnitAssignment extends Model
{
    use HasFactory;

    protected $table = 'employee_unit_assignments';

    protected $fillable = [
        'employee_id',
        'unit_id',
        'report_id',
        'assigned_by_admin_id',
        'verified_by_admin_id',
        'status',
        'priority',
        'notes',
        'assigned_at',
        'started_at',
        'completed_at',
        'verified_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function assignedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_by_admin_id');
    }

    public function verifiedByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by_admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Employee\EmployeeUnitAssignmentFactory::new();
    }
}