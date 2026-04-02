<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'session_token',
        'ip_address',
        'user_agent',
        'last_activity_at'
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function unitVisits()
    {
        return $this->hasMany(UnitVisit::class, 'session_id', 'session_token');
    }

    // Helper methods
    public function updateLastActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function isExpired($minutes = 30)
    {
        if (!$this->last_activity_at) {
            return true;
        }
        
        return $this->last_activity_at->diffInMinutes(now()) > $minutes;
    }
}