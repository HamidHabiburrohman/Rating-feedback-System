<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating_id',
        'admin_id',
        'reply_message',
        'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getFormattedReplyAttribute()
    {
        return nl2br(e($this->reply_message));
    }

    protected static function booted()
    {
        static::created(function ($reply) {
            $reply->rating->update([
                'last_replied_at' => $reply->replied_at
            ]);
        });
    }
}