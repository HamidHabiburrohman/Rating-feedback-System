<?php

namespace App\Services\Admin;

use App\Models\Rating;
use App\Models\Unit;
use App\Models\Student;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class RatingManagementService extends BaseAdminService
{
    protected array $searchableColumns = ['tracking_code', 'comment'];
    protected array $filterableColumns = ['unit_id', 'student_id', 'status'];

    public function __construct(Rating $rating)
    {
        $this->model = $rating;
        parent::__construct();
    }

    public function getPaginatedRatings(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters, ['unit', 'student', 'scores.category', 'adminReply']);

            if (!empty($filters['min_score'])) {
                $query->where('overall_score', '>=', $filters['min_score']);
            }

            if (!empty($filters['max_score'])) {
                $query->where('overall_score', '<=', $filters['max_score']);
            }

            if (isset($filters['has_reports'])) {
                if ($filters['has_reports']) {
                    $query->whereHas('activeReport');
                } else {
                    $query->whereDoesntHave('activeReport');
                }
            }

            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('RatingManagementService::getPaginatedRatings error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            
            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? 10,
                1,
                ['path' => request()->url()]
            );
        }
    }

    public function moderate(int $ratingId, string $action, ?string $reason = null): bool
    {
        $rating = $this->find($ratingId);
        
        switch ($action) {
            case 'censor':
                $rating->is_comment_censored = true;
                $rating->save();
                $this->logAdminAction('censor_comment', $rating, $reason);
                break;
            case 'archive':
                $rating->status = 'archived';
                $rating->save();
                $this->logAdminAction('archive_rating', $rating, $reason);
                break;
            case 'restore':
                $rating->status = 'active';
                $rating->save();
                $this->logAdminAction('restore_rating', $rating, $reason);
                break;
            default:
                throw new \Exception('Tindakan tidak valid');
        }

        return true;
    }

    public function getFilterData(): array
    {
        return [
            'units' => Unit::orderBy('name')->pluck('name', 'id'),
            'students' => Student::orderBy('name')->pluck('name', 'id'),
            'statuses' => [
                '' => 'Semua Status',
                'active' => 'Aktif',
                'edited' => 'Diedit',
                'archived' => 'Diarsipkan'
            ]
        ];
    }

    public function getRatingDetail(int $id): Rating
    {
        return $this->model->with([
            'unit', 
            'student', 
            'scores.category', 
            'adminReply.admin', 
            'report'
        ])->findOrFail($id);
    }

    public function getRatingStats(?int $unitId = null): array
    {
        $query = $this->model->query();

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        return [
            'total' => $query->count(),
            'average' => round($query->avg('overall_score') ?? 0, 2),
            'active' => (clone $query)->where('status', 'active')->count(),
            'edited' => (clone $query)->where('status', 'edited')->count(),
            'archived' => (clone $query)->where('status', 'archived')->count(),
            'censored' => (clone $query)->where('is_comment_censored', true)->count(),
            'with_replies' => (clone $query)->has('adminReply')->count(),
            'with_reports' => (clone $query)->has('activeReport')->count()
        ];
    }

    public function getMonthlyStats(): array
    {
        $stats = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            
            $stats[] = [
                'month' => $month,
                'total' => $this->model
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'active' => $this->model
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'active')
                    ->count()
            ];
        }

        return $stats;
    }

    public function getUnitRanking(int $limit = 10): array
    {
        return Unit::where('total_ratings', '>', 0)
            ->orderByDesc('avg_rating')
            ->limit($limit)
            ->get(['id', 'name', 'avg_rating', 'total_ratings'])
            ->toArray();
    }

    public function updateRating(int $id, array $data): Rating
    {
        return $this->update($id, $data);
    }

    public function bulkAction(array $ratingIds, string $action): int
    {
        $count = 0;

        foreach ($ratingIds as $id) {
            try {
                switch ($action) {
                    case 'archive':
                        $this->update($id, ['status' => 'archived']);
                        $count++;
                        break;
                    case 'restore':
                        $this->update($id, ['status' => 'active']);
                        $count++;
                        break;
                    case 'delete':
                        $this->delete($id);
                        $count++;
                        break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->logAdminAction('bulk_' . $action, (object)[
            'count' => $count, 
            'ids' => $ratingIds
        ]);

        return $count;
    }

    public function getAllRatings(array $filters = [])
    {
        return $this->getBaseQuery($filters);
    }

    public function deleteRating(int $id): bool
    {
        $rating = $this->find($id);
        
        return $this->delete($id);
    }

    public function export(array $filters = [])
    {
        $query = $this->getBaseQuery($filters, ['unit', 'student']);

        if (!empty($filters['min_score'])) {
            $query->where('overall_score', '>=', $filters['min_score']);
        }

        if (!empty($filters['max_score'])) {
            $query->where('overall_score', '<=', $filters['max_score']);
        }

        $ratings = $query->get();

        $filename = 'ratings-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($ratings) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Tracking Code',
                'Unit',
                'Student',
                'Overall Score',
                'Status',
                'Has Reports',
                'Created At'
            ]);

            foreach ($ratings as $rating) {
                fputcsv($file, [
                    $rating->tracking_code,
                    $rating->unit->name ?? '-',
                    $rating->student->name ?? '-',
                    $rating->overall_score,
                    $rating->status,
                    $rating->reports()->exists() ? 'Yes' : 'No',
                    $rating->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}