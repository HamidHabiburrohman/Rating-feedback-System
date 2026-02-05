<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'unit_id',
        'visitor_session_id',
        'komentar',
        'status',
        'metadata',
        'dibalas_pada'
    ];

    protected $casts = [
        'metadata' => 'array',
        'dibalas_pada' => 'datetime',
        'created_at' => 'datetime'
    ];

    protected $appends = ['status_label', 'status_warna'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function visitorSession(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class, 'visitor_session_id');
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'pending' => 'Pending',
                'dibalas' => 'Dibalas',
                'selesai' => 'Selesai',
                default => ucfirst($this->status)
            }
        );
    }

    protected function statusWarna(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'pending' => 'warning',
                'dibalas' => 'info',
                'selesai' => 'success',
                default => 'secondary'
            }
        );
    }
}