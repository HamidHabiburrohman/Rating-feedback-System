<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        'permissions',
        'preferences',
        'login_count',
        'last_login_at',
        'last_login_ip',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'permissions' => 'array',
        'preferences' => 'array',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'login_count' => 'integer',
    ];

    public function unitPhotos()
    {
        return $this->hasMany(UnitPhoto::class, 'uploaded_by_admin_id');
    }

    public function adminReplies()
    {
        return $this->hasMany(AdminReply::class, 'admin_id');
    }

    public function handledReports()
    {
        return $this->hasMany(Report::class, 'admin_id');
    }

    public function moderationLogs()
    {
        return $this->hasMany(ModerationLog::class, 'admin_id');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isUnitAdmin(): bool
    {
        return $this->role === 'unit';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        return null;
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->nama));
        $initials = '';

        foreach ($words as $word) {
            if (strlen($initials) >= 2) {
                break;
            }
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials ?: 'AD';
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'bg-danger',
            'admin' => 'bg-primary',
            'unit' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Administrator',
            'unit' => 'Unit Admin',
            default => ucfirst($this->role),
        };
    }

    public function updateLastLogin(?string $ip = null): void
    {
        $this->updateQuietly([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
            'login_count' => $this->login_count + 1,
        ]);
    }

    public function getPreference(string $key, mixed $default = null): mixed
    {
        $preferences = $this->preferences ?? [];
        return data_get($preferences, $key, $default);
    }

    public function setPreference(string $key, mixed $value): void
    {
        $preferences = $this->preferences ?? [];
        data_set($preferences, $key, $value);
        $this->update(['preferences' => $preferences]);
    }

    public function mergePreferences(array $values): void
    {
        $preferences = array_merge($this->preferences ?? [], $values);
        $this->update(['preferences' => $preferences]);
    }
}
