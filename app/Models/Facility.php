<?php

namespace App\Models;

use App\Services\Admin\IconService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_key',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_facilities')
            ->withTimestamps();
    }

    // Scope
    public function scopeWithUnitsCount($query)
    {
        return $query->withCount('units');
    }

    public function getIconSvg()
    {
        if (empty($this->icon_key)) {
            return $this->getDefaultIconSvg();
        }

        try {
            $iconService = app(IconService::class);
            return $iconService->getSvg($this->icon_key, ['class' => 'w-6 h-6']);
        } catch (\Exception $e) {
            return $this->getDefaultIconSvg();
        }
    }
}
