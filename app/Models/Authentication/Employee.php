<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'name', 'email', 'password', 'employee_id', 'photo', 'phone',
        'position', 'department', 'is_active', 'timezone', 'preferences',
        'login_count', 'last_login_at', 'last_login_ip'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'preferences' => 'array',
        'login_count' => 'integer',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function unitAssignments()
    {
        return $this->hasMany(\App\Models\Employees\EmployeeUnitAssignment::class);
    }

    public function assignedUnits()
    {
        return $this->belongsToMany(\App\Models\Units\Unit::class, 'employee_unit_assignments')
                    ->withPivot('role_in_unit', 'assigned_at', 'ended_at', 'is_active')
                    ->withTimestamps();
    }

    public function ratingReplies()
    {
        return $this->hasMany(\App\Models\Feedback\RatingReply::class);
    }

    public function reportReplies()
    {
        return $this->hasMany(\App\Models\Reports\ReportReply::class);
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\System\Notification::class, 'notifiable');
    }

    protected static function newFactory()
    {
        return \Database\Factories\EmployeeFactory::new();
    }
}