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

    protected $appends = [
        'thumbnail_url'
    ];

    protected function getMetadataArray(): array
    {
        if (is_array($this->metadata)) {
            return $this->metadata;
        }

        if (is_string($this->metadata)) {
            $decoded = json_decode($this->metadata, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

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
        return $this->hasOne(UnitPhoto::class, 'unit_id')->where('is_primary', true);
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
        if (!$this->relationLoaded('primaryPhoto')) {
            $this->load('primaryPhoto');
        }

        $primary = $this->getRelation('primaryPhoto');

        if ($primary && $primary instanceof UnitPhoto) {
            return $primary->thumbnail_path ?? $primary->original_path ?? null;
        }

        if ($this->relationLoaded('photos')) {
            $firstPhoto = $this->photos->first();
            if ($firstPhoto) {
                return $firstPhoto->thumbnail_path ?? $firstPhoto->original_path ?? null;
            }
        }

        return null;
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

    public function getOpenDaysStartAttribute(): ?string
    {
        $metadata = $this->getMetadataArray();
        return $metadata['open_days_start'] ?? 'monday';
    }

    public function setOpenDaysStartAttribute(?string $value): void
    {
        $metadata = $this->getMetadataArray();
        $metadata['open_days_start'] = $value;
        $this->metadata = $metadata;
    }

    public function getOpenDaysEndAttribute(): ?string
    {
        $metadata = $this->getMetadataArray();
        return $metadata['open_days_end'] ?? 'friday';
    }

    public function setOpenDaysEndAttribute(?string $value): void
    {
        $metadata = $this->getMetadataArray();
        $metadata['open_days_end'] = $value;
        $this->metadata = $metadata;
    }

    public function getOpenDaysListAttribute(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $start = $this->open_days_start;
        $end = $this->open_days_end;

        $startIndex = array_search($start, $days);
        $endIndex = array_search($end, $days);

        if ($startIndex === false || $endIndex === false) {
            return $days;
        }

        if ($startIndex <= $endIndex) {
            return array_slice($days, $startIndex, $endIndex - $startIndex + 1);
        }

        return array_merge(
            array_slice($days, $startIndex),
            array_slice($days, 0, $endIndex + 1)
        );
    }

    public function getClosedDaysAttribute(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $openDays = $this->open_days_list;
        return array_values(array_diff($days, $openDays));
    }

    public function getOperationalScheduleAttribute(): array
    {
        $daysMap = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu'
        ];

        $openDays = $this->open_days_list;
        $closedDays = $this->closed_days;
        $openTime = $this->open_time ? date('H:i', strtotime($this->open_time)) : null;
        $closeTime = $this->close_time ? date('H:i', strtotime($this->close_time)) : null;

        $schedule = [];

        if (!empty($openDays) && $openTime && $closeTime) {
            if (count($openDays) === 7) {
                $schedule[] = [
                    'days' => 'Setiap Hari',
                    'open' => $openTime,
                    'close' => $closeTime,
                    'is_closed' => false
                ];
            } else {
                $ranges = $this->groupConsecutiveDays($openDays, $daysMap);
                foreach ($ranges as $range) {
                    $schedule[] = [
                        'days' => $range,
                        'open' => $openTime,
                        'close' => $closeTime,
                        'is_closed' => false
                    ];
                }
            }
        }

        if (!empty($closedDays)) {
            $closedRanges = $this->groupConsecutiveDays($closedDays, $daysMap);
            foreach ($closedRanges as $range) {
                $schedule[] = [
                    'days' => $range,
                    'open' => null,
                    'close' => null,
                    'is_closed' => true
                ];
            }
        }

        usort($schedule, function ($a, $b) {
            $order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $aDay = is_string($a['days']) ? explode(' - ', $a['days'])[0] : $a['days'];
            $bDay = is_string($b['days']) ? explode(' - ', $b['days'])[0] : $b['days'];
            $aIndex = array_search($aDay, $order);
            $bIndex = array_search($bDay, $order);
            return $aIndex <=> $bIndex;
        });

        return $schedule;
    }

    private function groupConsecutiveDays(array $days, array $daysMap): array
    {
        if (empty($days)) {
            return [];
        }

        $dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $dayIndices = array_flip($dayOrder);

        usort($days, function ($a, $b) use ($dayIndices) {
            return $dayIndices[$a] <=> $dayIndices[$b];
        });

        $ranges = [];
        $currentRange = [$days[0]];

        for ($i = 1; $i < count($days); $i++) {
            $prevIndex = $dayIndices[$days[$i - 1]];
            $currIndex = $dayIndices[$days[$i]];

            if ($currIndex === $prevIndex + 1) {
                $currentRange[] = $days[$i];
            } else {
                $ranges[] = $this->formatDayRange($currentRange, $daysMap);
                $currentRange = [$days[$i]];
            }
        }

        $ranges[] = $this->formatDayRange($currentRange, $daysMap);

        return $ranges;
    }

    private function formatDayRange(array $days, array $daysMap): string
    {
        if (count($days) === 1) {
            return $daysMap[$days[0]];
        }

        return $daysMap[$days[0]] . ' - ' . $daysMap[end($days)];
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

    public function getRatingStatsAttribute(): array
    {
        $ratingService = app(\App\Services\Student\RatingService::class);
        return $ratingService->getRatingStats($this->id);
    }

    public function getCategoryAveragesAttribute(): array
    {
        $ratingService = app(\App\Services\Student\RatingService::class);
        $stats = $ratingService->getRatingStats($this->id);
        return $stats['by_category'] ?? [];
    }
}
