<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'unit_type_id',
        'unit_department_id',
        'description',
        'location',
        'building',
        'floor',
        'phone',
        'email',
        'open_time',
        'close_time',
        'capacity',
        'is_active',
        'operational_status',
        'avg_rating',
        'total_ratings',
        'avg_facility_score',
        'avg_service_score',
        'avg_quality_score',
        'last_rated_at',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'capacity' => 'integer',
        'avg_rating' => 'decimal:2',
        'total_ratings' => 'integer',
        'avg_facility_score' => 'decimal:2',
        'avg_service_score' => 'decimal:2',
        'avg_quality_score' => 'decimal:2',
        'last_rated_at' => 'datetime',
        'metadata' => 'array',
        'deleted_at' => 'datetime'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(UnitDepartment::class, 'unit_department_id');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'unit_facilities')
            ->withTimestamps();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UnitPhoto::class)->orderBy('sort_order');
    }

    public function primaryPhoto(): HasOne
    {
        return $this->hasOne(UnitPhoto::class)->where('is_primary', true);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function activeRatings(): HasMany
    {
        return $this->hasMany(Rating::class)->where('status', 'active');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(UnitVisit::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        $primary = $this->primaryPhoto;
        return $primary ? $primary->thumbnail_path : null;
    }

    public function getIsOpenAttribute(): ?bool
    {
        if (!$this->open_time || !$this->close_time) {
            return null;
        }

        $now = now();
        $open = now()->setTimeFromTimeString($this->open_time);
        $close = now()->setTimeFromTimeString($this->close_time);

        return $now->between($open, $close);
    }

    public function getFormattedScoresAttribute(): array
    {
        return [
            'overall' => $this->avg_rating,
            'facility' => $this->avg_facility_score,
            'service' => $this->avg_service_score,
            'quality' => $this->avg_quality_score,
            'total_ratings' => $this->total_ratings
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('unit_type_id', $typeId);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('unit_department_id', $departmentId);
    }

    public function scopeByBuilding($query, $building)
    {
        return $query->where('building', $building);
    }

    public function scopeByFloor($query, $floor)
    {
        return $query->where('floor', $floor);
    }

    public function scopeByOperationalStatus($query, $status)
    {
        return $query->where('operational_status', $status);
    }

    public function scopeWithFacilities($query, array $facilityIds)
    {
        return $query->whereHas('facilities', function ($q) use ($facilityIds) {
            $q->whereIn('facility_id', $facilityIds);
        });
    }

    public function scopeWithMinRating($query, $minRating)
    {
        return $query->where('avg_rating', '>=', $minRating);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    public function syncAverages(): void
    {
        $ratings = $this->activeRatings()
            ->with('scores.category')
            ->get();

        if ($ratings->isEmpty()) {
            $this->update([
                'avg_rating' => 0,
                'total_ratings' => 0,
                'avg_facility_score' => 0,
                'avg_service_score' => 0,
                'avg_quality_score' => 0,
                'last_rated_at' => null
            ]);
            return;
        }

        $totalRatings = $ratings->count();
        $avgOverall = $ratings->avg('overall_score');

        $scores = [
            'facility' => [],
            'service' => [],
            'quality' => []
        ];

        foreach ($ratings as $rating) {
            foreach ($rating->scores as $score) {
                $categorySlug = $score->category->slug;
                if (isset($scores[$categorySlug])) {
                    $scores[$categorySlug][] = $score->score;
                }
            }
        }

        $avgFacility = !empty($scores['facility']) ? array_sum($scores['facility']) / count($scores['facility']) : 0;
        $avgService = !empty($scores['service']) ? array_sum($scores['service']) / count($scores['service']) : 0;
        $avgQuality = !empty($scores['quality']) ? array_sum($scores['quality']) / count($scores['quality']) : 0;

        $this->update([
            'avg_rating' => round($avgOverall, 2),
            'total_ratings' => $totalRatings,
            'avg_facility_score' => round($avgFacility, 2),
            'avg_service_score' => round($avgService, 2),
            'avg_quality_score' => round($avgQuality, 2),
            'last_rated_at' => $ratings->max('created_at')
        ]);
    }

    public function incrementTotalRatings(): void
    {
        $this->increment('total_ratings');
    }

    public function decrementTotalRatings(): void
    {
        if ($this->total_ratings > 0) {
            $this->decrement('total_ratings');
        }
    }
}
