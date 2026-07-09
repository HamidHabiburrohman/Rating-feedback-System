<?php

namespace App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'facilities';

    protected $fillable = ['name', 'slug', 'icon_key', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function units()
    {
        return $this->belongsToMany(\App\Models\Unit\Unit::class, 'unit_facilities')
                    ->withPivot('value')
                    ->withTimestamps();
    }

    protected static function newFactory()
    {
        return \Database\Factories\Facility\FacilityFactory::new();
    }
}