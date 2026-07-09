<?php

namespace App\Models\Authentication;

use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Feedback\RatingReply;
use App\Models\Message\Message;
use App\Models\Report\ReportReply;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'photo',
        'phone',
        'position',
        'department',
        'is_active',
        'timezone',
        'preferences',
        'login_count',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'preferences' => 'array',
        'login_count' => 'integer',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function employeeAssignments(): HasMany
    {
        return $this->hasMany(EmployeeUnitAssignment::class);
    }

    public function assignedUnits(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'employee_unit_assignments')
            ->withPivot('assigned_at', 'status', 'priority', 'notes')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function ratingReplies(): HasMany
    {
        return $this->hasMany(RatingReply::class);
    }

    public function reportReplies(): HasMany
    {
        return $this->hasMany(ReportReply::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(\App\Models\System\Notification::class, 'notifiable');
    }

    protected static function newFactory()
    {
        return \Database\Factories\Employee\EmployeeFactory::new();
    }
}