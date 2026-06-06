<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    protected $table = 'exports';

    protected $fillable = [
        'admin_id', 'export_type', 'format', 'file_name', 'file_path',
        'file_size', 'status', 'filters', 'completed_at', 'expires_at',
        'download_count', 'last_downloaded_at'
    ];

    protected $casts = [
        'filters' => 'array',
        'file_size' => 'integer',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'download_count' => 'integer',
        'last_downloaded_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\Authentication\Admin::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    protected static function newFactory()
    {
        return \Database\Factories\ExportFactory::new();
    }
}