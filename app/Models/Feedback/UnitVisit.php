<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitVisit extends Model
{
    use HasFactory;

    protected $table = 'unit_visits';

    protected $fillable = [
        'unit_id', 'student_id', 'qr_code_id', 'visited_at',
        'is_gps_validated', 'latitude', 'longitude',
        'validation_radius_meters', 'metadata'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_gps_validated' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'validation_radius_meters' => 'integer',
        'metadata' => 'array'
    ];

    public function unit()
    {
        return $this->belongsTo(\App\Models\Units\Unit::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\Authentication\Student::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(\App\Models\Units\QrCode::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Unit\UnitVisitFactory::new();
    }
}