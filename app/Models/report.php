<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'rating_id',
        'unit_id',
        'student_id',
        'title',
        'description',
        'priority',
        'status',
        'admin_id',
        'admin_response',
        'replied_at'
    ];

    protected $casts = [
        'priority' => 'string',
        'status' => 'string',
        'replied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Accessors
    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritis',
            default => $this->priority
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'new' => 'Baru',
            'in_progress' => 'Diproses',
            'replied' => 'Ditanggapi',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
            'pending_preview' => 'Menunggu Pratinjau',
            default => $this->status
        };
    }

    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'low' => 'blue',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'new' => 'blue',
            'in_progress' => 'yellow',
            'replied' => 'purple',
            'resolved' => 'green',
            'rejected' => 'red',
            'pending_preview' => 'orange',
            default => 'gray'
        };
    }

    // Scope
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('tracking_code', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('student', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('student_identifier', 'like', "%{$search}%");
                });
        });
    }

    // Helper methods
    public function assignToAdmin($adminId)
    {
        $this->update([
            'admin_id' => $adminId,
            'status' => 'in_progress'
        ]);
    }

    public function respond($response, $adminId)
    {
        $this->update([
            'admin_id' => $adminId,
            'admin_response' => $response,
            'status' => 'replied',
            'replied_at' => now()
        ]);
    }

    public function resolve()
    {
        $this->update(['status' => 'resolved']);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    protected static function booted()
    {
        static::creating(function ($report) {
            $report->tracking_code = self::generateTrackingCode();
        });
    }

    protected static function generateTrackingCode()
    {
        do {
            $code = 'RPT-' . strtoupper(uniqid());
        } while (static::where('tracking_code', $code)->exists());

        return $code;
    }
}
