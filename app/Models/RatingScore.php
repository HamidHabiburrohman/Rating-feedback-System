<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating_id',
        'rating_category_id',
        'score'
    ];

    protected $casts = [
        'score' => 'decimal:1',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function category()
    {
        return $this->belongsTo(RatingCategory::class, 'rating_category_id');
    }

    // Scope
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('rating_category_id', $categoryId);
    }

    public function scopeMinScore($query, $min)
    {
        return $query->where('score', '>=', $min);
    }

    public function scopeMaxScore($query, $max)
    {
        return $query->where('score', '<=', $max);
    }
}