<?php

namespace App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unit_types';

    protected $fillable = ['name', 'slug', 'icon_key', 'description', 'is_active'];

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
        return \Database\Factories\UnitTypeFactory::new();
    }
}