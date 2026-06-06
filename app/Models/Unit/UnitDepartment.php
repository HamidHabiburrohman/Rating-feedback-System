<?php

namespace App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unit_departments';

    protected $fillable = ['name', 'slug', 'code', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\UnitDepartmentFactory::new();
    }
}