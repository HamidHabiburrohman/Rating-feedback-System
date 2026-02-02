<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'session_id',
        'visitor_ip',
        'user_agent',
        'komentar',
        'status',
        'metadata',
        'dibalas_pada'
    ];

    protected $casts = [
        'metadata' => 'array',
        'dibalas_pada' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Scope untuk filter
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDibalas($query)
    {
        return $query->where('status', 'dibalas');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isDibalas()
    {
        return $this->status === 'dibalas';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    public function markAsDibalas()
    {
        $this->update([
            'status' => 'dibalas',
            'dibalas_pada' => now()
        ]);
    }

    public function markAsSelesai()
    {
        $this->update(['status' => 'selesai']);
    }

    // Get rating attributes from metadata
    public function getRatingAttributes()
    {
        if (!empty($this->metadata) && is_array($this->metadata)) {
            return [
                'kebersihan' => $this->metadata['kebersihan'] ?? 0,
                'pelayanan' => $this->metadata['pelayanan'] ?? 0,
                'kecepatan' => $this->metadata['kecepatan'] ?? 0,
                'keramahan' => $this->metadata['keramahan'] ?? 0,
                'fasilitas' => $this->metadata['fasilitas'] ?? 0
            ];
        }

        return [
            'kebersihan' => 0,
            'pelayanan' => 0,
            'kecepatan' => 0,
            'keramahan' => 0,
            'fasilitas' => 0
        ];
    }

    public function getAverageRating()
    {
        if (!empty($this->metadata) && is_array($this->metadata)) {
            $values = array_values($this->metadata);
            return array_sum($values) / count($values);
        }
        return 0;
    }


    public function getRatingStars()
    {
        $average = $this->getAverageRating();
        return str_repeat('★', floor($average)) . str_repeat('☆', 5 - floor($average));
    }
}