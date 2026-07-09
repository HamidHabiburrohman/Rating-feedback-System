<?php

declare(strict_types=1);

namespace App\Models\Conversation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'original_name',
        'stored_name',
        'mime_type',
        'extension',
        'size',
        'path',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}