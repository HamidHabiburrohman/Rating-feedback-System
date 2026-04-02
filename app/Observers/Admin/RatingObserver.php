<?php

namespace App\Observers\Admin;

use App\Models\Rating;
use App\Models\Unit;
use App\Models\ModerationLog;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RatingObserver
{
    public function created(Rating $rating): void
    {
        $this->updateUnitRatingCache($rating->unit_id);
    }

    public function updated(Rating $rating): void
    {
        if ($rating->isDirty(['overall_score', 'status']) || $rating->wasChanged()) {
            $this->updateUnitRatingCache($rating->unit_id);
        }

        if ($rating->isDirty('status') && $rating->status === 'archived') {
            $this->logModeration('archived', $rating);
        }
    }

    public function deleted(Rating $rating): void
    {
        $this->updateUnitRatingCache($rating->unit_id);
    }

    public function restored(Rating $rating): void
    {
        $this->updateUnitRatingCache($rating->unit_id);
    }

    protected function updateUnitRatingCache(int $unitId): void
    {
        $ratings = Rating::where('unit_id', $unitId)
            ->whereIn('status', ['active', 'edited'])
            ->get();

        $totalRatings = $ratings->count();

        if ($totalRatings === 0) {
            Unit::where('id', $unitId)->update([
                'avg_rating' => 0,
                'total_ratings' => 0,
                'avg_facility_score' => 0,
                'avg_service_score' => 0,
                'avg_quality_score' => 0,
                'last_rated_at' => null,
            ]);
            return;
        }

        $avgRating = $ratings->avg('overall_score');
        $lastRatedAt = $ratings->max('created_at');

        $scores = DB::table('rating_scores')
            ->join('ratings', 'ratings.id', '=', 'rating_scores.rating_id')
            ->join('rating_categories', 'rating_categories.id', '=', 'rating_scores.rating_category_id')
            ->where('ratings.unit_id', $unitId)
            ->whereIn('ratings.status', ['active', 'edited'])
            ->select(
                DB::raw("AVG(CASE WHEN rating_categories.slug = 'facility' THEN rating_scores.score END) as avg_facility"),
                DB::raw("AVG(CASE WHEN rating_categories.slug = 'service' THEN rating_scores.score END) as avg_service"),
                DB::raw("AVG(CASE WHEN rating_categories.slug = 'quality' THEN rating_scores.score END) as avg_quality")
            )
            ->first();

        Unit::where('id', $unitId)->update([
            'avg_rating' => round($avgRating, 2),
            'total_ratings' => $totalRatings,
            'avg_facility_score' => round($scores->avg_facility ?? 0, 2),
            'avg_service_score' => round($scores->avg_service ?? 0, 2),
            'avg_quality_score' => round($scores->avg_quality ?? 0, 2),
            'last_rated_at' => $lastRatedAt,
        ]);

        Cache::forget("unit.{$unitId}.ratings");
        Cache::forget("unit.{$unitId}.stats");
    }

    protected function logModeration(string $action, Rating $rating): void
    {
        try {
            ModerationLog::create([
                'admin_id' => FacadesAuth::id(),
                'action' => 'rating_' . $action,
                'target_type' => 'rating',
                'target_id' => $rating->id,
                'metadata' => json_encode([
                    'unit_id' => $rating->unit_id,
                    'student_id' => $rating->student_id,
                    'overall_score' => $rating->overall_score,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log rating moderation: ' . $e->getMessage());
        }
    }
}