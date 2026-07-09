<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModerationLog extends Model
{
    use HasFactory;

    protected $table = 'moderation_logs';

    protected $fillable = [
        'admin_id', 'action', 'target_type', 'target_id',
        'reason', 'metadata', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class);
    }

    public function target()
    {
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return \Database\Factories\Moderation\ModerationLogFactory::new();
    }
}