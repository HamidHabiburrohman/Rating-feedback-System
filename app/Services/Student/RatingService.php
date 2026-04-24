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

    public function hasUserRated(int $unitId, string $studentIdentifier): bool
    {
        return $this->rating->where('unit_id', $unitId)
            ->where('student_identifier', $studentIdentifier)
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
            $score = $scores[$category['id']] ?? null;
            $category['score'] = $score ? $score->score : null;
            return $category;
        }, $categories);
    }

    public function submitRating(array $data, string $studentIdentifier): Rating
    {
        return DB::transaction(function () use ($data, $studentIdentifier) {
            $unit = Unit::findOrFail($data['unit_id']);

            $existing = $this->rating
                ->where('unit_id', $unit->id)
                ->where('student_identifier', $studentIdentifier)
                ->first();

            if ($existing) {
                return $this->updateRating($existing, $data);
            }

            $overallScore = round(array_sum($data['scores']) / count($data['scores']), 2);

            $rating = $this->rating->create([
                'tracking_code' => 'RTG-' . strtoupper(uniqid()),
                'unit_id' => $unit->id,
                'student_identifier' => $studentIdentifier,
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

            $this->syncUnitAverages($unit);
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

            $this->syncUnitAverages($rating->unit);

            return $rating->fresh(['scores.category']);
        });
    }

    private function syncUnitAverages(Unit $unit): void
    {
        $activeRatings = $this->rating
            ->where('unit_id', $unit->id)
            ->whereIn('status', ['active', 'edited'])
            ->get();

        if ($activeRatings->isEmpty()) {
            $unit->update([
                'avg_rating' => 0,
                'total_ratings' => 0,
                'avg_facility_score' => 0,
                'avg_service_score' => 0,
                'avg_quality_score' => 0,
                'last_rated_at' => null
            ]);
            return;
        }

        $totalRatings = $activeRatings->count();
        $avgOverall = $activeRatings->avg('overall_score');

        $categoryScores = [
            'facility' => [],
            'service' => [],
            'quality' => []
        ];

        $categories = $this->getActiveCategories();
        $categorySlugToField = [
            'facility' => 'avg_facility_score',
            'service' => 'avg_service_score',
            'quality' => 'avg_quality_score'
        ];

        foreach ($activeRatings as $rating) {
            foreach ($rating->scores as $score) {
                $category = $score->category;
                if ($category && isset($categorySlugToField[$category->slug])) {
                    $categoryScores[$category->slug][] = $score->score;
                }
            }
        }

        $updateData = [
            'avg_rating' => round($avgOverall, 2),
            'total_ratings' => $totalRatings,
            'last_rated_at' => $activeRatings->max('created_at')
        ];

        foreach ($categorySlugToField as $slug => $field) {
            $scores = $categoryScores[$slug] ?? [];
            $average = !empty($scores) ? array_sum($scores) / count($scores) : 0;
            $updateData[$field] = round($average, 2);
        }

        $unit->update($updateData);
    }

    public function findByTrackingCode(string $trackingCode): Rating
    {
        return $this->rating->with(['unit', 'scores.category'])
            ->where('tracking_code', $trackingCode)
            ->firstOrFail();
    }

    public function getUserRatings(string $studentIdentifier, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->rating->with(['unit', 'scores.category'])
            ->where('student_identifier', $studentIdentifier);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('unit', function ($q2) use ($filters) {
                    $q2->where('name', 'like', "%{$filters['search']}%");
                })->orWhere('comment', 'like', "%{$filters['search']}%");
            });
        }

        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 10;

        return $query->orderBy($sort, $order)->paginate($perPage);
    }

    public function getUnitRatings(int $unitId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->rating->with(['student', 'scores.category'])
            ->where('unit_id', $unitId)
            ->whereIn('status', ['active', 'edited']);

        if (!empty($filters['sort']) && $filters['sort'] === 'highest') {
            $query->orderByDesc('overall_score');
        } elseif (!empty($filters['sort']) && $filters['sort'] === 'lowest') {
            $query->orderBy('overall_score');
        } else {
            $query->latest();
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage);
    }

    public function getRatingStats(int $unitId): array
    {
        $ratings = $this->rating->where('unit_id', $unitId)
            ->whereIn('status', ['active', 'edited']);

        $categories = $this->getActiveCategories();
        $categoryAverages = [];

        foreach ($categories as $category) {
            $avg = $this->ratingScore->whereHas('rating', function ($q) use ($unitId) {
                $q->where('unit_id', $unitId)->whereIn('status', ['active', 'edited']);
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
        $studentIdentifier = auth('student')->user()->student_identifier;

        if ($rating->student_identifier !== $studentIdentifier) {
            return false;
        }

        if ($rating->status === 'archived') {
            return false;
        }

        $hasActiveReport = $rating->activeReport()->exists();

        if ($hasActiveReport) {
            return false;
        }

        return true;
    }

    private function updateUnitAverage(Unit $unit): void
    {
        $avg = $this->rating
            ->where('unit_id', $unit->id)
            ->whereIn('status', ['active', 'edited'])
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