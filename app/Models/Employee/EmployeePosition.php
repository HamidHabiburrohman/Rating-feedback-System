<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_positions';

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function employees()
    {
        return $this->hasMany(\App\Models\Authentication\Employee::class, 'position_id');
    }
}