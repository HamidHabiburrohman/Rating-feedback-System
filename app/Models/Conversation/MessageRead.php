<?php

declare(strict_types=1);

namespace App\Models\Conversation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MessageRead extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'reader_type',
        'reader_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function reader(): MorphTo
    {
        return $this->morphTo();
    }
}