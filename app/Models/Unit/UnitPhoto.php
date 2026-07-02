<?php

namespace App\Models\Unit;

use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unit_photos';

    protected $fillable = [
        'unit_id', 'uploaded_by_admin_id', 'disk', 'original_path',
        'thumbnail_path', 'medium_path', 'large_path', 'file_name',
        'mime_type', 'file_size', 'alt_text', 'sort_order', 'is_primary'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function uploadedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by_admin_id');
    }
    protected static function newFactory()
    {
        return \Database\Factories\UnitPhotoFactory::new();
    }
}