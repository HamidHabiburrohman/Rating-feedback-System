<?php

namespace App\Models\Unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitFacility extends Model
{
    use HasFactory;

    protected $table = 'unit_facilities';

    protected $fillable = ['unit_id', 'facility_id', 'value'];

    protected $casts = [
        'value' => 'string',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}