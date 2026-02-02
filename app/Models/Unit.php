<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'type_id',
        'lokasi',
        'foto_unit',
        'gedung',
        'lantai',
        'kapasitas',
        'kontak_telepon',
        'kontak_email',
        'jam_buka',
        'jam_tutup',
        'deskripsi',
        'status_aktif',
        'status'
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];

    public function unitType()
    {
        return $this->belongsTo(UnitType::class, 'type_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeFull($query)
    {
        return $query->where('status', 'full');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
    public function getJenisUnitAttribute()
    {
        return $this->unitType->name ?? null;
    }
}