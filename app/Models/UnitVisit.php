<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitVisit extends Model
{
    use HasFactory;

    protected $table = 'unit_visits';

    protected $fillable = [
        'unit_id',
        'session_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_detik',
        'metadata'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'durasi_detik' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function studentSession()
    {
        return $this->belongsTo(StudentSession::class, 'session_id', 'session_token');
    }

    // Accessors
    public function getDurasiFormattedAttribute()
    {
        if (!$this->durasi_detik) {
            return '-';
        }

        $jam = floor($this->durasi_detik / 3600);
        $menit = floor(($this->durasi_detik % 3600) / 60);
        $detik = $this->durasi_detik % 60;

        if ($jam > 0) {
            return "{$jam}j {$menit}m";
        } elseif ($menit > 0) {
            return "{$menit}m {$detik}d";
        } else {
            return "{$detik}d";
        }
    }

    // Scope
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('waktu_keluar');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('waktu_keluar');
    }

    // Helper methods
    public function endVisit()
    {
        if ($this->waktu_keluar) {
            return $this;
        }

        $this->waktu_keluar = now();
        $this->durasi_detik = $this->waktu_masuk->diffInSeconds($this->waktu_keluar);
        $this->save();

        return $this;
    }
}