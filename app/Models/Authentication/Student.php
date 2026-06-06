<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'student_identifier',
        'name',
        'email',
        'password',
        'is_active',
        'major',
        'class_year',
        'bio',
        'phone',
        'location',
        'portfolio_url',
        'linkedin_url',
        'photo',
        'last_login_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function ratings()
    {
        return $this->hasMany(\App\Models\Feedback\Rating::class);
    }

    public function reports()
    {
        return $this->hasMany(\App\Models\Reports\Report::class);
    }

    public function unitVisits()
    {
        return $this->hasMany(\App\Models\Feedback\UnitVisit::class);
    }
    public function notifications()
    {
        return $this->morphMany(\App\Models\System\Notification::class, 'notifiable');
    }

    protected static function newFactory()
    {
        return \Database\Factories\StudentFactory::new();
    }
}
