<?php

namespace App\Observers;

use App\Models\Feedback\Rating;
use App\Models\Unit\Unit;
use Illuminate\Support\Facades\Cache;

class RatingObserver
{
    public function created(Rating $rating): void
    {
        $this->updateUnitStatistics($rating->unit_id);
        $this->clearCache($rating);
    }

    public function updated(Rating $rating): void
    {
        if ($rating->isDirty(['overall_score', 'status'])) {
            $this->updateUnitStatistics($rating->unit_id);
        }
        $this->clearCache($rating);
    }

    public function deleted(Rating $rating): void
    {
        $this->updateUnitStatistics($rating->unit_id);
        $this->clearCache($rating);
    }

    public function restored(Rating $rating): void
    {
        $this->updateUnitStatistics($rating->unit_id);
        $this->clearCache($rating);
    }

    protected function updateUnitStatistics(int $unitId): void
    {
        $unit = Unit::find($unitId);
        if (!$unit) return;

        $stats = Rating::where('unit_id', $unitId)
            ->whereIn('status', ['active', 'edited'])
            ->selectRaw('COUNT(*) as total, AVG(overall_score) as avg')
            ->first();

        $unit->update([
            'total_ratings' => $stats->total ?? 0,
            'avg_rating' => round($stats->avg ?? 0, 2),
        ]);
    }

    protected function clearCache(Rating $rating): void
    {
        Cache::tags(["unit_{$rating->unit_id}"])->flush();
        Cache::tags(['ratings'])->flush();
        Cache::tags(['landing'])->flush();
        Cache::tags(['dashboard'])->flush();
        Cache::tags(["student_{$rating->student_id}"])->flush();
    }
}