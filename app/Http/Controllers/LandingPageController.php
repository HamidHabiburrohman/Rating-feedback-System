<?php
// app/Http/Controllers/LandingPageController.php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitType;
use App\Services\Student\RatingService;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    protected RatingService $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index()
    {
        $totalUnits = Unit::active()->count();
        $totalCategories = UnitType::active()->count();
        
        $totalMembers = 2400;
        
        $avgRatingOverall = round(Unit::active()->avg('avg_rating') ?? 0, 1);

        // PASTIKAN LOAD primaryPhoto dan photos
        $topRatedUnits = Unit::active()
            ->with(['primaryPhoto', 'type', 'department', 'photos'])
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($unit) {
                $fullStars = floor($unit->avg_rating);
                $hasHalf = ($unit->avg_rating - $fullStars) >= 0.5;
                
                return (object)[
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'slug' => $unit->slug,
                    'description' => $unit->description,
                    'avg_rating' => $unit->avg_rating ?? 0,
                    'total_ratings' => $unit->total_ratings ?? 0,
                    'primaryPhoto' => $unit->primaryPhoto,
                    'photos' => $unit->photos,
                    'type_name' => $unit->type?->name,
                    'full_stars' => $fullStars,
                    'has_half' => $hasHalf,
                    'empty_stars' => 5 - $fullStars - ($hasHalf ? 1 : 0),
                    'rating_display' => number_format($unit->avg_rating, 1)
                ];
            });

        if ($topRatedUnits->count() < 3) {
            $existingIds = $topRatedUnits->pluck('id')->toArray();
            
            $additionalUnits = Unit::active()
                ->with(['primaryPhoto', 'type', 'department', 'photos'])
                ->whereNotIn('id', $existingIds)
                ->orderBy('created_at', 'desc')
                ->limit(3 - $topRatedUnits->count())
                ->get()
                ->map(function ($unit) {
                    $fullStars = floor($unit->avg_rating);
                    $hasHalf = ($unit->avg_rating - $fullStars) >= 0.5;
                    
                    return (object)[
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'slug' => $unit->slug,
                        'description' => $unit->description,
                        'avg_rating' => $unit->avg_rating ?? 0,
                        'total_ratings' => $unit->total_ratings ?? 0,
                        'primaryPhoto' => $unit->primaryPhoto,
                        'photos' => $unit->photos,
                        'type_name' => $unit->type?->name,
                        'full_stars' => $fullStars,
                        'has_half' => $hasHalf,
                        'empty_stars' => 5 - $fullStars - ($hasHalf ? 1 : 0),
                        'rating_display' => number_format($unit->avg_rating, 1)
                    ];
                });
            
            $topRatedUnits = $topRatedUnits->concat($additionalUnits);
        }

        $recentUnits = Unit::active()
            ->with(['primaryPhoto', 'type', 'department'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($unit) {
                return (object)[
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'slug' => $unit->slug,
                    'avg_rating' => $unit->avg_rating ?? 0,
                    'total_ratings' => $unit->total_ratings ?? 0,
                    'primaryPhoto' => $unit->primaryPhoto,
                    'type_name' => $unit->type?->name,
                    'rating_display' => number_format($unit->avg_rating, 1)
                ];
            });

        $statistics = [
            'total_units' => $totalUnits,
            'total_members' => $totalMembers,
            'total_categories' => $totalCategories,
            'avg_rating_overall' => $avgRatingOverall,
        ];

        return view('landingpage.index', compact('topRatedUnits', 'recentUnits', 'statistics'));
    }
}