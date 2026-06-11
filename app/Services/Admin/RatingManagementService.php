<?php

namespace App\Services\Admin;

use App\Models\Feedback\Rating;
use App\Models\System\ModerationLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RatingManagementService extends BaseAdminService
{
    public function getFilteredRatings(array $filters = [])
    {
        $query = Rating::with(['student', 'unit', 'scores.category']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhere('tracking_code', 'like', "%{$search}%")
                  ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('unit', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        if (!empty($filters['min_score'])) {
            $query->where('overall_score', '>=', $filters['min_score']);
        }

        if (!empty($filters['max_score'])) {
            $query->where('overall_score', '<=', $filters['max_score']);
        }

        $sort = $filters['sort'] ?? 'latest';
        switch ($sort) {
            case 'highest':
                $query->orderByDesc('overall_score');
                break;
            case 'lowest':
                $query->orderBy('overall_score');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getDetail(int $id): array
    {
        $cacheKey = "admin_rating_detail_{$id}";

        return Cache::tags(['ratings', "rating_{$id}"])->remember($cacheKey, 300, function () use ($id) {
            $rating = Rating::with([
                'student',
                'unit',
                'scores.category',
                'reports.student',
                'replies.employee',
                'visit',
            ])->findOrFail($id);

            return [
                'rating' => $rating,
                'moderation_logs' => ModerationLog::where('target_type', Rating::class)
                    ->where('target_id', $id)
                    ->with('admin')
                    ->latest()
                    ->get(),
            ];
        });
    }

    public function moderate(int $id, string $action, ?string $reason, int $adminId): bool
    {
        return DB::transaction(function () use ($id, $action, $reason, $adminId) {
            $rating = Rating::findOrFail($id);
            $oldStatus = $rating->status;

            switch ($action) {
                case 'approve':
                    $rating->update(['status' => 'active']);
                    break;
                case 'reject':
                case 'archive':
                    $rating->update(['status' => 'archived']);
                    break;
                case 'censor_comment':
                    $rating->update([
                        'comment' => '[Komentar telah disensor oleh admin]',
                        'is_comment_censored' => true,
                    ]);
                    break;
                default:
                    throw new \Exception("Action '{$action}' tidak valid");
            }

            $this->logModeration($rating, $action, $oldStatus, $reason, $adminId);

            Cache::tags(['ratings', "rating_{$id}", "unit_{$rating->unit_id}", 'landing', 'dashboard'])->flush();

            return true;
        });
    }

    public function updateStatus(int $id, string $status): bool
    {
        return DB::transaction(function () use ($id, $status) {
            $rating = Rating::findOrFail($id);
            $rating->update(['status' => $status]);

            Cache::tags(['ratings', "rating_{$id}", "unit_{$rating->unit_id}", 'landing', 'dashboard'])->flush();

            return true;
        });
    }

    public function bulkAction(array $ids, string $action, ?string $reason, int $adminId): int
    {
        return DB::transaction(function () use ($ids, $action, $reason, $adminId) {
            $count = 0;
            $ratings = Rating::whereIn('id', $ids)->get();

            foreach ($ratings as $rating) {
                try {
                    $this->moderate($rating->id, $action, $reason, $adminId);
                    $count++;
                } catch (\Exception $e) {
                    continue;
                }
            }

            return $count;
        });
    }

    public function export(array $filters = [])
    {
        $query = Rating::with(['student', 'unit', 'scores.category']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $ratings = $query->get();

        $rows = $ratings->map(function ($rating) {
            return [
                'ID' => $rating->id,
                'Tracking Code' => $rating->tracking_code,
                'Unit' => $rating->unit?->name ?? '-',
                'Student' => $rating->student?->name ?? '-',
                'Score' => $rating->overall_score,
                'Comment' => $rating->is_comment_censored ? '[Censored]' : ($rating->comment ?? '-'),
                'Status' => $rating->status,
                'Created At' => $rating->created_at?->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        $headers = array_keys($rows[0] ?? []);
        $filename = 'ratings_' . now()->format('Y-m-d_His');

        $exportManager = app(\App\Services\Export\ExportManager::class);
        return $exportManager->getExcelService()->export($rows, $headers, 'Rating Export', $filename);
    }

    public function getStats(): array
    {
        return Cache::tags(['ratings', 'dashboard'])->remember('admin_rating_stats', 300, function () {
            $total = Rating::count();
            $active = Rating::where('status', 'active')->count();
            $edited = Rating::where('status', 'edited')->count();
            $archived = Rating::where('status', 'archived')->count();
            $censored = Rating::where('is_comment_censored', true)->count();
            $avgScore = round(Rating::avg('overall_score') ?? 0, 2);

            return [
                'total' => $total,
                'active' => $active,
                'edited' => $edited,
                'archived' => $archived,
                'censored' => $censored,
                'avg_score' => $avgScore,
                'distribution' => [
                    '5' => Rating::where('overall_score', '>=', 4.5)->count(),
                    '4' => Rating::whereBetween('overall_score', [3.5, 4.49])->count(),
                    '3' => Rating::whereBetween('overall_score', [2.5, 3.49])->count(),
                    '2' => Rating::whereBetween('overall_score', [1.5, 2.49])->count(),
                    '1' => Rating::where('overall_score', '<', 1.5)->count(),
                ],
            ];
        });
    }

    protected function logModeration(Rating $rating, string $action, string $oldStatus, ?string $reason, int $adminId): void
    {
        ModerationLog::create([
            'admin_id' => $adminId,
            'target_type' => Rating::class,
            'target_id' => $rating->id,
            'action' => $action,
            'old_value' => json_encode(['status' => $oldStatus, 'comment' => $rating->getOriginal('comment')]),
            'new_value' => json_encode(['status' => $rating->status, 'comment' => $rating->comment]),
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}