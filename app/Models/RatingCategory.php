<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function ratingScores()
    {
        return $this->hasMany(RatingScore::class);
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper methods
    public static function getDefaultCategories()
    {
        return [
            ['name' => 'Facility', 'slug' => 'facility', 'sort_order' => 1],
            ['name' => 'Service', 'slug' => 'service', 'sort_order' => 2],
            ['name' => 'Quality', 'slug' => 'quality', 'sort_order' => 3],
        ];
    }
}