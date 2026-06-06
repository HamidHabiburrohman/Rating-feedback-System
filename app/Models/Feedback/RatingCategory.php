<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rating_categories';

    protected $fillable = [
        'name', 'slug', 'is_active', 'sort_order',
        'min_score', 'max_score', 'default_score'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'min_score' => 'float',
        'max_score' => 'float',
        'default_score' => 'float',
        'deleted_at' => 'datetime',
    ];

    public function ratingScores()
    {
        return $this->hasMany(RatingScore::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\RatingCategoryFactory::new();
    }
}