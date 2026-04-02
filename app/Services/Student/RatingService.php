<?php

namespace App\Services\Student;

use App\Models\Rating;
use App\Models\RatingCategory;
use App\Models\RatingScore;
use App\Models\Unit;
use App\Models\UnitVisit;
use App\Models\StudentSession;
use Illuminate\Support\Facades\DB;

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
        return $this->rating->where('unit_id', $unitId)
            ->where('student_id', $studentId)
            ->where('status', '!=', 'archived')
            ->exists();
    }

    public function getActiveCategories(): array
    {
        return $this->ratingCategory->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug'])
            ->toArray();
    }

    public function getActiveCategoriesWithScores(Rating $rating): array
    {
        $categories = $this->getActiveCategories();
        $scores = $rating->scores->keyBy('rating_category_id');

        return array_map(function ($category) use ($scores) {
            $category['score'] = $scores[$category['id']]->score ?? null;
            return $category;
        }, $categories);
    }

    public function submitRating(array $data, int $studentId): Rating
    {
        return DB::transaction(function () use ($data, $studentId) {

            $unit = Unit::findOrFail($data['unit_id']);

            $existing = $this->rating
                ->where('unit_id', $unit->id)
                ->where('student_id', $studentId)
                ->first();

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
                'metadata' => json_encode([
                    'device' => request()->userAgent(),
                    'ip' => request()->ip()
                ])
            ]);

            foreach ($data['scores'] as $categoryId => $score) {
                $this->ratingScore->create([
                    'rating_id' => $rating->id,
                    'rating_category_id' => $categoryId,
                    'score' => $score
                ]);
            }

            $unit->increment('total_ratings');

            $this->updateUnitAverage($unit);
            $this->createUnitVisit($unit->id);

            return $rating->fresh(['unit', 'scores.category']);
        });
    }

    public function updateRating(Rating $rating, array $data): Rating
    {
        return DB::transaction(function () use ($rating, $data) {

            if (!$this->canEdit($rating)) {
                throw new \Exception('Tidak dapat mengedit rating ini');
            }

            $overallScore = round(array_sum($data['scores']) / count($data['scores']), 2);

            $rating->update([
                'overall_score' => $overallScore,
                'comment' => $data['comment'] ?? null,
                'status' => 'edited',
                'last_edited_at' => now()
            ]);

            foreach ($data['scores'] as $categoryId => $score) {
                $this->ratingScore->updateOrCreate(
                    [
                        'rating_id' => $rating->id,
                        'rating_category_id' => $categoryId
                    ],
                    ['score' => $score]
                );
            }

            $this->updateUnitAverage($rating->unit);

            return $rating->fresh(['scores.category']);
        });
    }

    public function findByTrackingCode(string $trackingCode): Rating
    {
        return $this->rating->with(['unit', 'student', 'scores.category'])
            ->where('tracking_code', $trackingCode)
            ->firstOrFail();
    }

    public function getUserRatings(int $studentId, array $filters = []): array
    {
        $query = $this->rating->with(['unit', 'scores.category'])
            ->where('student_id', $studentId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 10;

        return $query->orderBy($sort, $order)
            ->paginate($perPage)
            ->toArray();
    }

    public function getUnitRatings(int $unitId, array $filters = []): array
    {
        $query = $this->rating->with(['student'])
            ->where('unit_id', $unitId)
            ->where('status', 'active');

        if (!empty($filters['sort']) && $filters['sort'] === 'highest') {
            $query->orderByDesc('overall_score');
        } elseif (!empty($filters['sort']) && $filters['sort'] === 'lowest') {
            $query->orderBy('overall_score');
        } else {
            $query->latest();
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage)->toArray();
    }

    public function getRatingStats(int $unitId): array
    {
        $ratings = $this->rating->where('unit_id', $unitId)
            ->where('status', 'active');

        $categories = $this->getActiveCategories();
        $categoryAverages = [];

        foreach ($categories as $category) {
            $avg = $this->ratingScore->whereHas('rating', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId)->where('status', 'active');
            })
                ->where('rating_category_id', $category['id'])
                ->avg('score');

            $categoryAverages[$category['slug']] = round($avg ?? 0, 2);
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
            'by_category' => $categoryAverages
        ];
    }

    public function canEdit(Rating $rating): bool
    {
        return $this->canEditRating($rating);
    }

    private function updateUnitAverage(Unit $unit): void
    {
        $avg = $this->rating
            ->where('unit_id', $unit->id)
            ->where('status', 'active')
            ->avg('overall_score');

        $unit->update([
            'avg_rating' => round($avg ?? 0, 2)
        ]);
    }

    private function createUnitVisit(int $unitId): void
    {
        $sessionId = StudentSession::where('session_token', session()->getId())->value('id');

        if ($sessionId) {
            UnitVisit::create([
                'student_session_id' => $sessionId,
                'unit_id' => $unitId,
                'waktu_masuk' => now(),
                'waktu_keluar' => now(),
                'durasi_detik' => 0
            ]);
        }
    }
}
