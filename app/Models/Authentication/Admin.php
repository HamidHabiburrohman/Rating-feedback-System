<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'admins';

    protected $fillable = [
        'nama', 'email', 'password', 'role', 'photo', 'phone', 'location',
        'employee_id', 'position', 'department', 'bio', 'timezone', 'is_active',
        'two_factor_enabled', 'preferences', 'login_count', 'last_login_at', 'last_login_ip'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'preferences' => 'array',
        'login_count' => 'integer',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    protected static function newFactory()
    {
        return \Database\Factories\AdminFactory::new();
    }
}