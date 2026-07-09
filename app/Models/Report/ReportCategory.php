<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'report_categories';

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function reports()
    {
        return $this->hasMany(\App\Models\Report\Report::class);
    }

    protected static function newFactory()
    {
        return \Database\Factories\Report\ReportCategoryFactory::new();
    }
}