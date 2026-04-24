<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UnitPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'uploaded_by_admin_id',
        'original_path',
        'thumbnail_path',
        'file_name',
        'mime_type',
        'file_size',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by_admin_id');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path && !empty($this->thumbnail_path)) {
            if (filter_var($this->thumbnail_path, FILTER_VALIDATE_URL)) {
                return $this->thumbnail_path;
            }
            
            $path = $this->thumbnail_path;
            $path = str_replace('\\', '/', $path);
            $path = str_replace(public_path(), '', $path);
            $path = ltrim($path, '/');
            
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }
        
        if ($this->original_path && !empty($this->original_path)) {
            if (filter_var($this->original_path, FILTER_VALIDATE_URL)) {
                return $this->original_path;
            }
            
            $path = $this->original_path;
            $path = str_replace('\\', '/', $path);
            $path = str_replace(public_path(), '', $path);
            $path = ltrim($path, '/');
            
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }
        
        return null;
    }

    public function getOriginalUrlAttribute(): ?string
    {
        if ($this->original_path && !empty($this->original_path)) {
            if (filter_var($this->original_path, FILTER_VALIDATE_URL)) {
                return $this->original_path;
            }
            
            $path = $this->original_path;
            $path = str_replace('\\', '/', $path);
            $path = str_replace(public_path(), '', $path);
            $path = ltrim($path, '/');
            
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }
        
        return null;
    }
}