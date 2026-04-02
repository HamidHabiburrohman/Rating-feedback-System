<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModerationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'reason',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Accessors
    public function getTargetAttribute()
    {
        if (!$this->target_type || !$this->target_id) {
            return null;
        }

        try {
            $model = "App\\Models\\{$this->target_type}";
            return $model::find($this->target_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Scope
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByTargetType($query, $targetType)
    {
        return $query->where('target_type', $targetType);
    }

    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Helper methods
    public static function log($adminId, $action, $target, $reason = null, $metadata = [])
    {
        return self::create([
            'admin_id' => $adminId,
            'action' => $action,
            'target_type' => class_basename($target),
            'target_id' => $target->id,
            'reason' => $reason,
            'metadata' => $metadata
        ]);
    }
}