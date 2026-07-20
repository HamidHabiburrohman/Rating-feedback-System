<?php

namespace App\Models\Authentication;

use App\Models\Employee\EmployeeUnitAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'photo',
        'phone',
        'location',
        'employee_id',
        'position',
        'department',
        'bio',
        'timezone',
        'is_active',
        'two_factor_enabled',
        'preferences',
        'login_count',
        'last_login_at',
        'last_login_ip'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'preferences' => 'array',
        'login_count' => 'integer',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function updateLastLogin(?string $ip = null): void
    {
        $this->forceFill([
            'login_count' => ($this->login_count ?? 0) + 1,
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ])->save();
    }

    public function getPreference(string $key, $default = null)
    {
        return $this->preferences[$key] ?? $default;
    }

    public function mergePreferences(array $preferences): void
    {
        $current = $this->preferences ?? [];
        $this->preferences = array_merge($current, $preferences);
        $this->save();
    }

    public function assignedEmployeeAssignments(): HasMany
    {
        return $this->hasMany(EmployeeUnitAssignment::class, 'assigned_by_admin_id');
    }

    public function verifiedEmployeeAssignments(): HasMany
    {
        return $this->hasMany(EmployeeUnitAssignment::class, 'verified_by_admin_id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\Admin\AdminFactory::new();
    }
}
