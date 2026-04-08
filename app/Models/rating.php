<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_code',
        'unit_id',
        'student_identifier',
        'overall_score',
        'comment',
        'is_comment_censored',
        'status',
        'last_edited_at',
        'last_replied_at',
        'metadata'
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'is_comment_censored' => 'boolean',
        'last_edited_at' => 'datetime',
        'last_replied_at' => 'datetime',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_identifier', 'student_identifier');
    }

    public function scores()
    {
        return $this->hasMany(RatingScore::class);
    }

    public function adminReply()
    {
        return $this->hasOne(AdminReply::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function activeReport()
    {
        return $this->hasOne(Report::class)
            ->whereIn('status', ['new', 'in_progress', 'replied']);
    }

    public function getCommentDisplayAttribute()
    {
        if ($this->is_comment_censored) {
            return '[Komentar telah disensor oleh admin]';
        }
        return $this->comment;
    }

    public function getHasActiveReportAttribute()
    {
        return $this->activeReport()->exists();
    }

    public function getCanBeReportedAttribute()
    {
        if ($this->has_active_report) {
            return false;
        }

        $lastReport = $this->report()
            ->where('status', 'resolved')
            ->latest()
            ->first();

        if (!$lastReport) {
            return true;
        }

        if ($this->last_edited_at && $this->last_edited_at > $lastReport->updated_at) {
            return true;
        }

        return false;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWithActiveReport($query)
    {
        return $query->whereHas('activeReport');
    }

    public function scopeWithoutReport($query)
    {
        return $query->whereDoesntHave('activeReport');
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByStudent($query, $studentIdentifier)
    {
        return $query->where('student_identifier', $studentIdentifier);
    }

    public function scopeWithScores($query)
    {
        return $query->with('scores.category');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('tracking_code', 'like', "%{$search}%")
                ->orWhere('comment', 'like', "%{$search}%")
                ->orWhereHas('student', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('student_identifier', 'like', "%{$search}%");
                });
        });
    }

    public function calculateOverallScore()
    {
        $scores = $this->scores;

        if ($scores->isEmpty()) {
            return 0;
        }

        $total = $scores->sum('score');
        return round($total / $scores->count(), 2);
    }

    public function syncOverallScore()
    {
        $this->update([
            'overall_score' => $this->calculateOverallScore()
        ]);
    }

    public function markAsEdited()
    {
        $this->update([
            'status' => 'edited',
            'last_edited_at' => now()
        ]);
    }

    public function censorComment()
    {
        $this->update([
            'is_comment_censored' => true
        ]);
    }

    public function uncensorComment()
    {
        $this->update([
            'is_comment_censored' => false
        ]);
    }

    protected static function booted()
    {
        static::creating(function ($rating) {
            $rating->tracking_code = self::generateTrackingCode();
        });
    }

    protected static function generateTrackingCode()
    {
        do {
            $code = 'RTG-' . strtoupper(uniqid());
        } while (static::where('tracking_code', $code)->exists());

        return $code;
    }
}