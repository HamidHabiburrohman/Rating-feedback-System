<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relasi
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

    // Helper methods
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isUnitAdmin()
    {
        return $this->role === 'unit';
    }
}