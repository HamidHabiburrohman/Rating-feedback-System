<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_id',
        'uploaded_by_admin_id',
        'original_path',
        'thumbnail_path',
        'medium_path',
        'large_path',
        'file_name',
        'mime_type',
        'file_size',
        'alt_text',
        'sort_order',
        'is_primary'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_admin_id');
    }

    // Accessors
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->original_path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path 
            ? asset('storage/' . $this->thumbnail_path) 
            : $this->url;
    }

    public function getMediumUrlAttribute()
    {
        return $this->medium_path 
            ? asset('storage/' . $this->medium_path) 
            : $this->url;
    }

    public function getLargeUrlAttribute()
    {
        return $this->large_path 
            ? asset('storage/' . $this->large_path) 
            : $this->url;
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Scope
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper methods
    public function setAsPrimary()
    {
        // Reset primary pada foto lain di unit yang sama
        self::where('unit_id', $this->unit_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set foto ini sebagai primary
        $this->update(['is_primary' => true]);

        // Update atau create primary_photo_id di tabel units? 
        // Kalau mau simpan reference bisa ditambah kolom di units, tapi di migration belum ada
    }
}