<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'is_active',
        'units_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'units_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function syncUnitsCount()
    {
        $this->update([
            'units_count' => $this->units()->count()
        ]);
    }
}