<?php

namespace App\Services\Admin;

use App\Models\Rating;
use App\Models\Unit;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class RatingService
{
    public function getAllRatings($filters = [])
    {
        $query = Rating::with(['unit', 'visitorSession']);
        
        if (!empty($filters['unit'])) {
            $query->where('unit_id', $filters['unit']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('komentar', 'LIKE', "%{$search}%")
                  ->orWhereHas('unit', function($q) use ($search) {
                      $q->where('nama_unit', 'LIKE', "%{$search}%")
                        ->orWhere('kode_unit', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        return $query->orderBy($filters['sort'] ?? 'created_at', $filters['order'] ?? 'desc');
    }

    public function getRatingStats($unitId = null)
    {
        $query = Rating::query();
        if ($unitId) $query->where('unit_id', $unitId);
        
        $total = $query->count();
        $stats = $query->selectRaw("
            COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
            COUNT(CASE WHEN status = 'dibalas' THEN 1 END) as dibalas,
            COUNT(CASE WHEN status = 'selesai' THEN 1 END) as selesai
        ")->first();

        return [
            'total' => $total,
            'pending' => $stats->pending ?? 0,
            'dibalas' => $stats->dibalas ?? 0,
            'selesai' => $stats->selesai ?? 0,
            'average_rating' => $this->getAverageRating($unitId),
            'response_rate' => $total > 0 ? round((($stats->dibalas + $stats->selesai) / $total) * 100, 1) : 0
        ];
    }

    public function getAverageRating($unitId = null)
    {
        $query = Rating::query();
        if ($unitId) $query->where('unit_id', $unitId);
        
        $ratings = $query->get();
        if ($ratings->isEmpty()) return 0;

        $totalAvg = $ratings->avg(function ($rating) {
            $scores = is_array($rating->metadata) ? $rating->metadata : json_decode($rating->metadata, true);
            return $scores ? array_sum($scores) / count($scores) : 0;
        });

        return round($totalAvg, 1);
    }

    public function getUnitRanking($limit = 10)
    {
        return Unit::with(['ratings'])
            ->withCount('ratings')
            ->get()
            ->map(function($unit) {
                $unit->average_rating = $this->getAverageRating($unit->id);
                $unit->pending_count = $unit->ratings->where('status', 'pending')->count();
                $unit->dibalas_count = $unit->ratings->where('status', 'dibalas')->count();
                $unit->selesai_count = $unit->ratings->where('status', 'selesai')->count();
                return $unit;
            })
            ->filter(fn($unit) => $unit->ratings_count > 0)
            ->sortByDesc('average_rating')
            ->take($limit);
    }

    public function updateRating($id, array $data)
    {
        $rating = Rating::findOrFail($id);
        
        if (isset($data['status'])) {
            if ($data['status'] === 'dibalas' && $rating->status !== 'dibalas') {
                $data['dibalas_pada'] = now();
            }
        }
        
        $rating->update($data);
        return $rating;
    }

    public function respondToRating($id, array $responseData)
    {
        $rating = Rating::findOrFail($id);
        $metadata = is_array($rating->metadata) ? $rating->metadata : json_decode($rating->metadata, true);
        
        $metadata['admin_response'] = $responseData;
        
        $rating->update([
            'status' => 'dibalas',
            'dibalas_pada' => now(),
            'metadata' => $metadata
        ]);
        
        return $rating;
    }

    public function getMonthlyStats()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthRatings = Rating::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->get();

            $totalScore = $monthRatings->sum(function($r) {
                $scores = is_array($r->metadata) ? $r->metadata : json_decode($r->metadata, true);
                return $scores ? array_sum($scores) / count($scores) : 0;
            });

            $months[$date->format('Y-m')] = [
                'month' => $date->format('M Y'),
                'total' => $monthRatings->count(),
                'average_rating' => $monthRatings->count() > 0 ? round($totalScore / $monthRatings->count(), 1) : 0
            ];
        }
        return $months;
    }

    public function deleteRating($id)
    {
        return Rating::findOrFail($id)->delete();
    }
}