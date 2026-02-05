<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'unit_id',
        'visitor_session_id',
        'judul',
        'deskripsi',
        'tipe',
        'prioritas',
        'status',
        'admin_id',
        'tanggapan_admin',
        'ditanggapi_pada'
    ];

    protected $casts = [
        'ditanggapi_pada' => 'datetime',
        'created_at' => 'datetime'
    ];

    protected $appends = ['prioritas_warna', 'tipe_label', 'status_label'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    protected function prioritasWarna(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->prioritas) {
                'kritis' => 'danger',
                'tinggi' => 'warning',
                'sedang' => 'primary',
                'rendah' => 'secondary',
                default => 'secondary'
            }
        );
    }

    protected function tipeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->tipe) {
                'masalah' => 'Masalah',
                'saran' => 'Saran',
                'keluhan' => 'Keluhan',
                'lainnya' => 'Lainnya',
                default => ucfirst($this->tipe)
            }
        );
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                'baru' => 'Baru',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
                default => ucfirst($this->status)
            }
        );
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('tracking_code', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            $q->whereIn('status', explode(',', $status));
        });
    }

    public function scopeFilterByTipe($query, $tipe)
    {
        return $query->when($tipe, function ($q) use ($tipe) {
            $q->whereIn('tipe', explode(',', $tipe));
        });
    }

    public function scopeFilterByPrioritas($query, $prioritas)
    {
        return $query->when($prioritas, function ($q) use ($prioritas) {
            $q->whereIn('prioritas', explode(',', $prioritas));
        });
    }

    public function scopeFilterByUnit($query, $unit)
    {
        return $query->when($unit, function ($q) use ($unit) {
            $q->whereIn('unit_id', explode(',', $unit));
        });
    }

    public function scopeFilterByDate($query, $dateFrom, $dateTo)
    {
        return $query->when($dateFrom, function ($q) use ($dateFrom) {
            $q->whereDate('created_at', '>=', $dateFrom);
        })->when($dateTo, function ($q) use ($dateTo) {
            $q->whereDate('created_at', '<=', $dateTo);
        });
    }
}