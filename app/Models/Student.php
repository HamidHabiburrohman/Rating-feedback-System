<?php
// app/Models/Student.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Student extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    protected $fillable = [
        'student_identifier',
        'name',
        'email',
        'password',
        'major',
        'class_year',
        'bio',
        'phone',
        'location',
        'portfolio_url',
        'linkedin_url',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sessions()
    {
        return $this->hasMany(StudentSession::class, 'student_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'student_identifier', 'student_identifier');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'student_identifier', 'student_identifier');
    }

    public function hasRatedUnit($unitId)
    {
        return $this->ratings()
            ->where('unit_id', $unitId)
            ->where('status', '!=', 'archived')
            ->exists();
    }

    public function getActiveRatingForUnit($unitId)
    {
        return $this->ratings()
            ->where('unit_id', $unitId)
            ->where('status', '!=', 'archived')
            ->first();
    }

    public function getAuthIdentifierName()
    {
        return 'student_identifier';
    }

    public function getAuthIdentifier()
    {
        return $this->student_identifier;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && file_exists(storage_path('app/public/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=ad2b00&color=fff&size=256';
    }
}