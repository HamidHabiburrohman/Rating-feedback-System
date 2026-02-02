<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengirim_tipe',
        'pengirim_id',
        'penerima_tipe',
        'penerima_id',
        'judul',
        'pesan',
        'kategori',
        'prioritas',
        'status',
        'perlu_tindakan',
        'tipe_tindakan',
        'data_tindakan',
        'unit_id',
        'rating_id',
        'dibaca_pada',
        'tindakan_diambil_pada'
    ];

    protected $casts = [
        'perlu_tindakan' => 'boolean',
        'data_tindakan' => 'array',
        'dibaca_pada' => 'datetime',
        'tindakan_diambil_pada' => 'datetime'
    ];

    // Relations
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function sender()
    {
        return $this->morphTo('pengirim');
    }

    public function receiver()
    {
        return $this->morphTo('penerima');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['terkirim', 'diterima']);
    }

    public function scopeRequiresAction($query)
    {
        return $query->where('perlu_tindakan', true)
                     ->whereNull('tindakan_diambil_pada');
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('prioritas', $priority);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('kategori', $category);
    }

    public function scopeFromAdmin($query)
    {
        return $query->where('pengirim_tipe', 'admin');
    }

    public function scopeFromUnit($query)
    {
        return $query->where('pengirim_tipe', 'unit');
    }

    public function scopeToAdmin($query)
    {
        return $query->where('penerima_tipe', 'admin');
    }

    public function scopeToUnit($query)
    {
        return $query->where('penerima_tipe', 'unit');
    }

    // Helper methods
    public function markAsRead()
    {
        if (!$this->dibaca_pada) {
            $this->update([
                'status' => 'dibaca',
                'dibaca_pada' => now()
            ]);
        }
    }

    public function markAsResponded()
    {
        $this->update(['status' => 'ditanggapi']);
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'selesai']);
    }

    public function isUnread()
    {
        return in_array($this->status, ['terkirim', 'diterima']);
    }

    public function requiresAction()
    {
        return $this->perlu_tindakan && !$this->tindakan_diambil_pada;
    }
}