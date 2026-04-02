<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'subgroup',
        'label',
        'description',
        'hint',
        'options',
        'validation_rules',
        'sort_order',
        'is_editable',
        'is_visible',
        'is_public',
        'required_permission'
    ];

    protected $casts = [
        'options' => 'array',
        'is_editable' => 'boolean',
        'is_visible' => 'boolean',
        'is_public' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Accessor untuk value berdasarkan tipe
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => json_decode($this->value, true),
            'float' => (float) $this->value,
            default => $this->value,
        };
    }

    // Scope
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeEditable($query)
    {
        return $query->where('is_editable', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper methods
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->typed_value;
    }

    public static function set($key, $value)
    {
        $setting = self::firstOrCreate(['key' => $key]);
        $setting->value = $value;
        $setting->save();
        
        return $setting;
    }

    public static function getAllByGroup($group)
    {
        return self::group($group)
            ->ordered()
            ->get()
            ->keyBy('key')
            ->map(function ($setting) {
                return $setting->typed_value;
            });
    }
}