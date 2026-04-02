<?php

namespace App\Models;

use App\Services\Admin\IconService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UnitType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon_key',
        'description',
        'is_active',
        'units_count',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'units_count'=> 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'icon_svg',
        'icon_name',
        'icon_data',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc');
    }

    public function scopeWithUnitsCount($query)
    {
        return $query->withCount('units');
    }

    public function syncUnitsCount()
    {
        $this->update([
            'units_count' => $this->units()->count(),
        ]);
    }

    public function getIconSvgAttribute(): string
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

    public function getIconNameAttribute(): string
    {
        if (empty($this->icon_key)) {
            return 'Default';
        }

        try {
            $iconService = app(IconService::class);
            $icon = $iconService->getIcon($this->icon_key);
            return $icon['name'] ?? ucfirst($this->icon_key);
        } catch (\Exception $e) {
            return ucfirst($this->icon_key ?? 'Default');
        }
    }

    public function getIconDataAttribute(): mixed
    {
        if (empty($this->icon_key)) {
            return [
                'key' => null,
                'name' => 'Default',
                'svg' => $this->getDefaultIconSvg()
            ];
        }

        try {
            $iconService = app(IconService::class);
            return $iconService->getIconKey($this->icon_key);
        } catch (\Exception $e) {
            return [
                'key' => $this->icon_key,
                'name' => ucfirst($this->icon_key),
                'svg' => $this->getDefaultIconSvg()
            ];
        }
    }

    protected function getDefaultIconSvg(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($unitType) {
            if (empty($unitType->slug)) {
                $unitType->slug = Str::slug($unitType->name);
            }
            if (empty($unitType->icon_key)) {
                $unitType->icon_key = 'building';
            }
        });

        static::updating(function ($unitType) {
            if ($unitType->isDirty('name') && empty($unitType->slug)) {
                $unitType->slug = Str::slug($unitType->name);
            }
        });
    }
}