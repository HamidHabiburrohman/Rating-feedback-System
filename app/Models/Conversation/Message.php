<?php

declare(strict_types=1);

namespace App\Models\Conversation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_TEXT = 'text';
    public const TYPE_SYSTEM = 'system';
    public const TYPE_ATTACHMENT = 'attachment';

    protected $fillable = [
        'conversation_id',
        'sender_type',
        'sender_id',
        'body',
        'type',
        'edited_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function reads(): HasMany
    {
        return $this->hasMany(MessageRead::class);
    }
}