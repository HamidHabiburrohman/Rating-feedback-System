<?php

namespace App\Http\Controllers;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitType;
use App\Models\Authentication\Student;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        $topRatedUnits = Cache::tags(['landing', 'units'])->remember('landing_top_rated_units', 600, function () {
            return $this->getTopRatedUnits(3);
        });

        $recentUnits = Cache::tags(['landing', 'units'])->remember('landing_recent_units', 600, function () {
            return $this->getRecentUnits(6);
        });

        $statistics = Cache::tags(['landing'])->remember('landing_statistics', 300, function () {
            return $this->getStatistics();
        });

        return view('landing.index', compact('topRatedUnits', 'recentUnits', 'statistics'));
    }

    protected function getTopRatedUnits(int $limit): Collection
    {
        $topRatedUnits = Unit::where('is_active', true)
            ->with(['primaryPhoto', 'unitType', 'unitDepartment', 'photos'])
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($unit) => $this->mapUnitData($unit));

        if ($topRatedUnits->count() < $limit) {
            $existingIds = $topRatedUnits->pluck('id')->toArray();

            $additionalUnits = Unit::where('is_active', true)
                ->with(['primaryPhoto', 'unitType', 'unitDepartment', 'photos'])
                ->whereNotIn('id', $existingIds)
                ->orderBy('created_at', 'desc')
                ->limit($limit - $topRatedUnits->count())
                ->get()
                ->map(fn($unit) => $this->mapUnitData($unit));

            $topRatedUnits = $topRatedUnits->concat($additionalUnits);
        }

        return $topRatedUnits;
    }

    protected function getRecentUnits(int $limit): Collection
    {
        return Unit::where('is_active', true)
            ->with(['primaryPhoto', 'unitType', 'unitDepartment'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($unit) {
                return (object) [
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'slug' => $unit->slug,
                    'avg_rating' => $unit->avg_rating ?? 0,
                    'total_ratings' => $unit->total_ratings ?? 0,
                    'primaryPhoto' => $unit->primaryPhoto,
                    'type_name' => $unit->unitType?->name,
                    'rating_display' => number_format($unit->avg_rating ?? 0, 1)
    ];
            });
    }

    protected function getStatistics(): array
    {
        return [
            'total_units' => Unit::where('is_active', true)->count(),
            'total_members' => $this->getTotalMembers(),
            'total_categories' => UnitType::where('is_active', true)->count(),
            'avg_rating_overall' => round(Unit::where('is_active', true)->avg('avg_rating') ?? 0, 1)
    ];
    }

    protected function mapUnitData($unit): object
    {
        $avgRating = $unit->avg_rating ?? 0;
        $fullStars = (int) floor($avgRating);
        $hasHalf = ($avgRating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);

        return (object) [
            'id' => $unit->id,
            'name' => $unit->name,
            'slug' => $unit->slug,
            'description' => $unit->description,
            'avg_rating' => $avgRating,
            'total_ratings' => $unit->total_ratings ?? 0,
            'primaryPhoto' => $unit->primaryPhoto,
            'photos' => $unit->photos,
            'type_name' => $unit->unitType?->name,
            'full_stars' => $fullStars,
            'has_half' => $hasHalf,
            'empty_stars' => $emptyStars,
            'rating_display' => number_format($avgRating, 1)
    ];
    }

    protected function getTotalMembers(): int
    {
        return Cache::tags(['landing'])->remember('landing_total_members', 3600, function () {
            return Student::count() + Employee::count() + Admin::count();
        });
    }
}
