<?php

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
    ];

    protected $hidden = [
        'password',  
        'remember_token',  
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi
    public function sessions()
    {
        return $this->hasMany(StudentSession::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Helper methods
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
    
    // Optional: Method untuk auth via student_identifier
    public function getAuthIdentifierName()
    {
        return 'student_identifier';
    }
}