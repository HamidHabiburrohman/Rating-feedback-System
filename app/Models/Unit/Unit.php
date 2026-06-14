<?php

namespace App\Models\Unit;

use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Feedback\Rating;
use App\Models\Feedback\UnitVisit;
use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'unit_type_id',
        'unit_department_id',
        'description',
        'location',
        'building',
        'floor',
        'phone',
        'email',
        'open_time',
        'close_time',
        'capacity',
        'is_active',
        'operational_status',
        'primary_qr_code_id',
        'metadata',
        'avg_rating',
        'total_ratings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'capacity' => 'integer',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
        'avg_rating' => 'float',
        'total_ratings' => 'integer',
    ];

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }

    public function unitDepartment(): BelongsTo
    {
        return $this->belongsTo(UnitDepartment::class);
    }

    public function primaryQrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class, 'primary_qr_code_id');
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'unit_facilities')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UnitPhoto::class);
    }

    public function primaryPhoto(): HasOne
    {
        return $this->hasOne(UnitPhoto::class)->where('is_primary', true);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function unitVisits(): HasMany
    {
        return $this->hasMany(UnitVisit::class);
    }

    public function employeeAssignments(): HasMany
    {
        return $this->hasMany(EmployeeUnitAssignment::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\UnitFactory::new();
    }
}