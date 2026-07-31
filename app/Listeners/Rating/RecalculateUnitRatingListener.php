<?php

namespace App\Listeners\Rating;

use App\Events\Rating\RatingStatusUpdatedEvent;
use App\Events\Rating\RatingSubmittedEvent;
use App\Models\Feedback\Rating;
use App\Models\Unit\Unit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class RecalculateUnitRatingListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(object $event): void
    {
        try {
            $unitId = null;

            if ($event instanceof RatingSubmittedEvent) {
                $unitId = $event->rating->unit_id;
            } elseif ($event instanceof RatingStatusUpdatedEvent) {
                $unitId = $event->rating->unit_id;
            }

            if (!$unitId) {
                return;
            }

            $averageRating = Rating::where('unit_id', $unitId)
                ->where('status', 'approved')
                ->avg('score') ?? 0.0;

            $totalRatings = Rating::where('unit_id', $unitId)
                ->where('status', 'approved')
                ->count();

            Unit::where('id', $unitId)->update([
                'average_rating' => round($averageRating, 2),
                'total_ratings' => $totalRatings,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to recalculate unit rating: ' . $e->getMessage());
        }
    }
}