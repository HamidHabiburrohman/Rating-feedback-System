<?php

namespace App\Models\Unit;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'code', 'name', 'slug', 'unit_type_id', 'unit_department_id',
        'description', 'location', 'building', 'floor', 'phone', 'email',
        'open_time', 'close_time', 'capacity', 'is_active', 'operational_status',
        'primary_qr_code_id', 'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'capacity' => 'integer',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    public function unitDepartment()
    {
        return $this->belongsTo(UnitDepartment::class);
    }

    public function primaryQrCode()
    {
        return $this->belongsTo(QrCode::class, 'primary_qr_code_id');
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'unit_facilities')
                    ->withPivot('value')
                    ->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(UnitPhoto::class);
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Feedback\Rating::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function unitVisits()
    {
        return $this->hasMany(\App\Models\Feedback\UnitVisit::class);
    }

    public function employeeAssignments()
    {
        return $this->hasMany(\App\Models\Employee\EmployeeUnitAssignment::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\UnitFactory::new();
    }
}