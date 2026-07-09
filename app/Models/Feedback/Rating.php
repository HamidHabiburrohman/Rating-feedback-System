<?php

namespace App\Models\Feedback;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ratings';

    protected $fillable = [
        'tracking_code', 'unit_id', 'student_id', 'visit_id', 'qr_code_id',
        'overall_score', 'comment', 'is_comment_censored', 'status',
        'last_edited_at', 'last_replied_at', 'metadata'
    ];

    protected $casts = [
        'overall_score' => 'float',
        'is_comment_censored' => 'boolean',
        'last_edited_at' => 'datetime',
        'last_replied_at' => 'datetime',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit\Unit::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\Authentication\Student::class);
    }

    public function visit()
    {
        return $this->belongsTo(UnitVisit::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(\App\Models\Unit\QrCode::class);
    }

    public function scores()
    {
        return $this->hasMany(RatingScore::class);
    }

    public function attachments()
    {
        return $this->hasMany(RatingAttachment::class);
    }

    public function replies()
    {
        return $this->hasMany(RatingReply::class);
    }

    public function report()
    {
        return $this->hasOne(\App\Models\Report\Report::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Rating\RatingFactory::new();
    }
}