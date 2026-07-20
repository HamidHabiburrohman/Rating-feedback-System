<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rating_attachments';

    protected $fillable = [
        'rating_id', 'path', 'original_name', 'mime_type',
        'size', 'disk', 'sort_order'
    ];

    protected $casts = [
        'size' => 'integer',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime'
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Rating\RatingAttachmentFactory::new();
    }
}