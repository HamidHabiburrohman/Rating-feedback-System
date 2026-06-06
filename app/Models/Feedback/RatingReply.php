<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rating_replies';

    protected $fillable = ['rating_id', 'employee_id', 'reply', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\Authentication\Employee::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\RatingReplyFactory::new();
    }
}