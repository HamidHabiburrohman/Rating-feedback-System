<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'report_attachments';

    protected $fillable = [
        'report_id', 'path', 'original_name', 'mime_type',
        'size', 'disk', 'sort_order'
    ];

    protected $casts = [
        'size' => 'integer',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(\App\Models\Report\Report::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\ReportAttachmentFactory::new();
    }
}