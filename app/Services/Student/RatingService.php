<?php

namespace App\Services\Student;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingCategory;
use App\Models\Feedback\RatingScore;
use App\Models\Unit\Unit;
use App\Models\Unit\QrCode;
use App\Models\Authentication\Student;
use App\Models\Feedback\UnitVisit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Student\RatingSubmittedMail;
use Illuminate\Support\Facades\Log;

class RatingService extends BaseStudentService
{
    protected Rating $rating;
    protected RatingCategory $ratingCategory;
    protected RatingScore $ratingScore;

    public function __construct(Rating $rating, RatingCategory $ratingCategory, RatingScore $ratingScore)
    {
        $this->rating = $rating;
        $this->ratingCategory = $ratingCategory;
        $this->ratingScore = $ratingScore;
    }

    public function hasUserRated(int $unitId, int $studentId): bool
    {
        $cacheKey = "student_{$studentId}_unit_{$unitId}_rated";
        return Cache::tags(['ratings', "student_{$studentId}"])->remember($cacheKey, 600, function () use ($unitId, $studentId) {
            return $this->rating->where('unit_id', $unitId)->where('student_id', $studentId)->where('status', '!=', 'archived')->exists();
        });
    }

    public function getActiveCategories(): array
    {
        return Cache::tags(['ratings', 'dropdown'])->remember('active_rating_categories', 86400, function () {
            return $this->ratingCategory->where('is_active', true)->orderBy('sort_order')->get(['id', 'name', 'slug'])->toArray();
        });
    }

    public function getActiveCategoriesWithScores(Rating $rating): array
    {
        $categories = $this->getActiveCategories();
        $scores = $rating->scores->keyBy('rating_category_id');
        return array_map(function ($cat) use ($scores) {
            $cat['score'] = $scores[$cat['id']]?->score ?? null;
            return $cat;
        }, $categories);
    }

    public function submitRating(array $data, int $studentId): Rating
    {
        return DB::transaction(function () use ($data, $studentId) {
            $unit = Unit::findOrFail($data['unit_id']);
            $student = Student::findOrFail($studentId);

            $existing = $this->rating->where('unit_id', $unit->id)->where('student_id', $studentId)->first();
            if ($existing) {
                return $this->updateRating($existing, $data);
            }

            $overallScore = round(array_sum($data['scores']) / count($data['scores']), 2);

            $rating = $this->rating->create([
                'tracking_code' => 'RTG-' . strtoupper(uniqid()),
                'unit_id' => $unit->id,
                'student_id' => $studentId,
                'overall_score' => $overallScore,
                'comment' => $data['comment'] ?? null,
                'status' => 'active',
                'metadata' => json_encode(['device' => request()->userAgent(), 'ip' => request()->ip()])
            ]);

            foreach ($data['scores'] as $catId => $score) {
                $this->ratingScore->create(['rating_id' => $rating->id, 'rating_category_id' => $catId, 'score' => $score]);
            }

            $this->createUnitVisit($unit->id, $studentId);

            try {
                Mail::to($student->email)->send(new RatingSubmittedMail(
                    $student->name,
                    $unit->name,
                    $overallScore,
                    $rating->tracking_code
                ));
            } catch (\Exception $e) {
                Log::warning("Failed to send rating email: " . $e->getMessage());
            }

            return $rating->fresh(['unit', 'scores.category']);
        });
    }

    public function updateRating(Rating $rating, array $data): Rating
    {
        return DB::transaction(function () use ($rating, $data) {
            if (!$this->canEdit($rating)) throw new \Exception('Tidak dapat mengedit rating ini');

            $overallScore = round(array_sum($data['scores']) / count($data['scores']), 2);
            $rating->update([
                'overall_score' => $overallScore,
                'comment' => $data['comment'] ?? null,
                'status' => 'edited',
                'last_edited_at' => now()
            ]);

            foreach ($data['scores'] as $catId => $score) {
                $this->ratingScore->updateOrCreate(
                    ['rating_id' => $rating->id, 'rating_category_id' => $catId],
                    ['score' => $score]
                );
            }
            return $rating->fresh(['scores.category']);
        });
    }

    public function findByTrackingCode(string $trackingCode): Rating
    {
        return $this->rating->with(['unit', 'scores.category'])->where('tracking_code', $trackingCode)->firstOrFail();
    }

    public function getUserRatings(int $studentId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->rating->with(['unit', 'scores.category'])->where('student_id', $studentId);
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('unit', fn($u) => $u->where('name', 'like', "%{$filters['search']}%"))
                    ->orWhere('comment', 'like', "%{$filters['search']}%");
            });
        }
        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        return $query->orderBy($sort, $order)->paginate($filters['per_page'] ?? 10);
    }

    public function getUnitRatings(int $unitId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->rating->with(['student', 'scores.category'])->where('unit_id', $unitId)->whereIn('status', ['active', 'edited']);
        if (!empty($filters['sort']) && $filters['sort'] === 'highest') $query->orderByDesc('overall_score');
        elseif (!empty($filters['sort']) && $filters['sort'] === 'lowest') $query->orderBy('overall_score');
        else $query->latest();
        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function getRatingStats(int $unitId): array
    {
        $cacheKey = "unit_rating_stats_{$unitId}";
        return Cache::tags(['ratings', "unit_{$unitId}"])->remember($cacheKey, 600, function () use ($unitId) {
            $ratings = $this->rating->where('unit_id', $unitId)->whereIn('status', ['active', 'edited']);
            $categories = $this->getActiveCategories();
            $catAverages = [];
            foreach ($categories as $cat) {
                $avg = $this->ratingScore->whereHas('rating', fn($q) => $q->where('unit_id', $unitId)->whereIn('status', ['active', 'edited']))
                    ->where('rating_category_id', $cat['id'])->avg('score');
                $catAverages[$cat['slug']] = round($avg ?? 0, 2);
            }
            return [
                'total' => $ratings->count(),
                'average' => round($ratings->avg('overall_score') ?? 0, 2),
                'distribution' => [
                    '5' => $ratings->where('overall_score', '>=', 4.5)->count(),
                    '4' => $ratings->whereBetween('overall_score', [3.5, 4.49])->count(),
                    '3' => $ratings->whereBetween('overall_score', [2.5, 3.49])->count(),
                    '2' => $ratings->whereBetween('overall_score', [1.5, 2.49])->count(),
                    '1' => $ratings->where('overall_score', '<', 1.5)->count()
                ],
                'by_category' => $catAverages
            ];
        });
    }

    public function canEdit(Rating $rating): bool
    {
        $studentId = auth('student')->id();
        if ($rating->student_id !== $studentId) return false;
        if ($rating->status === 'archived') return false;
        if ($rating->reports()->whereIn('status', ['new', 'in_progress'])->exists()) return false;
        $lastResolvedReport = $rating->reports()->where('status', 'resolved')->latest()->first();
        if ($lastResolvedReport && $lastResolvedReport->updated_at->addDays(7) > now()) return false;
        return true;
    }

    private function createUnitVisit(int $unitId, int $studentId): void
    {
        $qrCode = QrCode::where('unit_id', $unitId)->where('is_active', true)->first();
        UnitVisit::create([
            'unit_id' => $unitId,
            'student_id' => $studentId,
            'qr_code_id' => $qrCode?->id,
            'visited_at' => now(),
            'is_gps_validated' => false,
            'latitude' => null,
            'longitude' => null,
            'validation_radius_meters' => 100,
        ]);
    }
}
