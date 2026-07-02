<?php

namespace App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'unit_id',
        'code',
        'qr_image_path',
        'is_active',
        'expires_at',
        'generated_by_admin_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit\Unit::class);
    }

    public function generatedByAdmin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class, 'generated_by_admin_id');
    }

    public function unitVisits()
    {
        return $this->hasMany(\App\Models\Feedback\UnitVisit::class);
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Feedback\Rating::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\QrCodeFactory::new();
    }
}