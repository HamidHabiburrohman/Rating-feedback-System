<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingScore extends Model
{
    use HasFactory;

    protected $table = 'rating_scores';

    protected $fillable = ['rating_id', 'rating_category_id', 'score'];

    protected $casts = [
        'score' => 'float'
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function category()
    {
        return $this->belongsTo(RatingCategory::class, 'rating_category_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\Rating\RatingScoreFactory::new();
    }
}