<?php

namespace App\Services\Admin;

use App\Models\Unit;
use App\Models\Rating;
use App\Models\UnitVisit;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    public function getOverview()
    {
        try {
            $data = [
                'top_rated_units' => $this->getTopUnits('all')['data'] ?? []
            ];

            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Error in getOverview: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => ['top_rated_units' => []]
            ];
        }
    }

    public function getStats()
    {
        try {
            $today = now()->startOfDay();
            $weekStart = now()->startOfWeek();

            $visitorsToday = UnitVisit::whereDate('tanggal', $today)->count();
            $visitorsWeek = UnitVisit::where('tanggal', '>=', $weekStart)->count();
            $visitorsYesterday = UnitVisit::whereDate('tanggal', now()->subDay()->startOfDay())->count();

            $ratingsToday = Rating::whereDate('created_at', $today)->count();
            $ratingsYesterday = Rating::whereDate('created_at', now()->subDay()->startOfDay())->count();

            $activeUnits = Unit::where('is_active', true)->count();
            $totalUnits = Unit::count();
            $unitsLastWeek = Unit::where('created_at', '>=', $weekStart)->count();

            $visitorTrend = $visitorsYesterday > 0
                ? round((($visitorsToday - $visitorsYesterday) / $visitorsYesterday) * 100)
                : ($visitorsToday > 0 ? 100 : 0);

            $ratingTrend = $ratingsYesterday > 0
                ? round((($ratingsToday - $ratingsYesterday) / $ratingsYesterday) * 100)
                : ($ratingsToday > 0 ? 100 : 0);

            $unitTrend = $totalUnits > 0
                ? round(($unitsLastWeek / $totalUnits) * 100)
                : 0;

            $unitGrowth = $this->getUnitGrowthData();

            return [
                'success' => true,
                'data' => [
                    'visitors' => [
                        'today' => $visitorsToday,
                        'this_week' => $visitorsWeek,
                        'trend' => [
                            'daily' => $visitorTrend,
                            'weekly' => $unitTrend
                        ]
                    ],
                    'ratings' => [
                        'today' => $ratingsToday,
                        'trend' => $ratingTrend
                    ],
                    'units' => [
                        'active' => $activeUnits,
                        'total' => $totalUnits,
                        'trend' => $unitTrend
                    ],
                    'unit_growth' => $unitGrowth
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in getStats: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [
                    'visitors' => ['today' => 0, 'this_week' => 0, 'trend' => ['daily' => 0, 'weekly' => 0]],
                    'ratings' => ['today' => 0, 'trend' => 0],
                    'units' => ['active' => 0, 'total' => 0, 'trend' => 0],
                    'unit_growth' => $this->getMockUnitGrowthData()
                ]
            ];
        }
    }

    protected function getUnitGrowthData()
    {
        try {
            $months = [];
            $newUnits = [];
            $cumulative = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthName = $date->format('M');
                $months[] = $monthName;

                $count = Unit::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $newUnits[] = $count;
            }

            $total = 0;
            foreach ($newUnits as $count) {
                $total += $count;
                $cumulative[] = $total;
            }

            return [
                'months' => $months,
                'new_units' => $newUnits,
                'cumulative' => $cumulative
            ];
        } catch (\Exception $e) {
            return $this->getMockUnitGrowthData();
        }
    }

    protected function getMockUnitGrowthData()
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $total = Unit::count();

        $newUnits = [];
        $cumulative = [];
        $runningTotal = 0;

        for ($i = 0; $i < 12; $i++) {
            if ($i < 6) {
                $count = rand(1, 5);
            } else {
                $count = rand(5, 15);
            }
            $newUnits[] = $count;
            $runningTotal += $count;
            $cumulative[] = $runningTotal;
        }

        if ($runningTotal > 0 && $total > 0) {
            $factor = $total / $runningTotal;
            $newUnits = array_map(function ($val) use ($factor) {
                return round($val * $factor);
            }, $newUnits);

            $runningTotal = 0;
            $cumulative = [];
            foreach ($newUnits as $count) {
                $runningTotal += $count;
                $cumulative[] = $runningTotal;
            }
        }

        return [
            'months' => $months,
            'new_units' => $newUnits,
            'cumulative' => $cumulative
        ];
    }

    public function getTopUnits(string $type = 'all')
    {
        switch ($type) {
            case 'popularity':
                return $this->getMostPopularUnits();
            case 'quality':
                return $this->getTopRatedUnits();
            case 'attention':
                return $this->getAttentionUnits();
            case 'all':
            default:
                return $this->getAllUnits();
        }
    }

    public function getAllUnits()
    {
        try {
            $units = Unit::with(['type', 'primaryPhoto'])
                ->withCount('ratings as total_ratings')
                ->where('is_active', true)
                ->orderBy('name', 'asc')
                ->limit(5)
                ->get();

            return [
                'success' => true,
                'data' => $this->formatUnitsResponse($units)
            ];
        } catch (\Exception $e) {
            Log::error('Error in getAllUnits: ' . $e->getMessage());
            return ['success' => false, 'data' => []];
        }
    }

    public function getMostPopularUnits()
    {
        try {
            $units = Unit::with(['type', 'primaryPhoto'])
                ->withCount('ratings as total_ratings')
                ->where('is_active', true)
                ->where('total_ratings', '>', 0)
                ->orderBy('total_ratings', 'desc')
                ->limit(5)
                ->get();

            return [
                'success' => true,
                'data' => $this->formatUnitsResponse($units)
            ];
        } catch (\Exception $e) {
            Log::error('Error in getMostPopularUnits: ' . $e->getMessage());
            return ['success' => false, 'data' => []];
        }
    }

    public function getTopRatedUnits()
    {
        try {
            $units = Unit::with(['type', 'primaryPhoto'])
                ->withCount('ratings as total_ratings')
                ->where('is_active', true)
                ->where('total_ratings', '>', 0)
                ->orderBy('avg_rating', 'desc')
                ->limit(5)
                ->get();

            return [
                'success' => true,
                'data' => $this->formatUnitsResponse($units)
            ];
        } catch (\Exception $e) {
            Log::error('Error in getTopRatedUnits: ' . $e->getMessage());
            return ['success' => false, 'data' => []];
        }
    }

    public function getAttentionUnits()
    {
        try {
            $units = Unit::with(['type', 'primaryPhoto'])
                ->withCount('ratings as total_ratings')
                ->where('is_active', true)
                ->where('avg_rating', '<', 2.5)
                ->where('total_ratings', '>', 0)
                ->orderBy('avg_rating', 'asc')
                ->limit(5)
                ->get();

            Log::info('Attention units found: ' . $units->count());

            return [
                'success' => true,
                'data' => $this->formatUnitsResponse($units)
            ];
        } catch (\Exception $e) {
            Log::error('Error in getAttentionUnits: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => []
            ];
        }
    }

    protected function formatUnitsResponse($units)
    {
        $result = [];
        foreach ($units as $unit) {
            $result[] = [
                'id' => $unit->id,
                'name' => $unit->name,
                'code' => $unit->code,
                'type_name' => $unit->type ? $unit->type->name : 'General',
                'unit_type' => $unit->type ? [
                    'id' => $unit->type->id,
                    'name' => $unit->type->name,
                    'slug' => $unit->type->slug
                ] : null,
                'avg_rating' => round($unit->avg_rating ?? 0, 1),
                'rata_rata_rating' => round($unit->avg_rating ?? 0, 1),
                'total_ratings' => $unit->total_ratings ?? 0,
                'total_rating' => $unit->total_ratings ?? 0,
                'is_active' => $unit->is_active,
                'status' => $unit->is_active ? 'Aktif' : 'Tidak Aktif',
                'status_aktif' => $unit->is_active,
                'thumbnail' => $unit->primaryPhoto ? $unit->primaryPhoto->thumbnail_url : null,
                'is_open' => $unit->is_open
            ];
        }

        return $result;
    }

    public function getRecentRated()
    {
        try {
            $ratings = Rating::with(['unit', 'unit.type', 'student'])
                ->where('status', 'active')
                ->latest()
                ->limit(5)
                ->get();

            return [
                'success' => true,
                'data' => $ratings->map(function ($rating) {
                    return [
                        'id' => $rating->id,
                        'unit_id' => $rating->unit_id,
                        'unit_name' => $rating->unit->name ?? 'Unknown Unit',
                        'unit_type' => $rating->unit->type->name ?? 'General',
                        'student_name' => $rating->student->name ?? 'Anonymous',
                        'rating' => round($rating->overall_score, 1),
                        'comment' => $rating->comment,
                        'date' => $rating->created_at->toDateTimeString(),
                        'date_formatted' => $rating->created_at->diffForHumans(),
                        'time_ago' => $rating->created_at->diffForHumans()
                    ];
                })
            ];
        } catch (\Exception $e) {
            Log::error('Error in getRecentRated: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => []
            ];
        }
    }

    public function getAuditLogs()
    {
        try {
            return [
                'success' => true,
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'data' => []
            ];
        }
    }

    public function getCharts()
    {
        try {
            $visitationTrend = [];
            $unitGrowth = $this->getUnitGrowthData();

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $visits = UnitVisit::whereDate('tanggal', $date)->count();
                $visitationTrend[] = [
                    'day' => $date->format('D'),
                    'date' => $date->format('Y-m-d'),
                    'visitors' => $visits
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'visitation_trend' => $visitationTrend,
                    'unit_growth' => $unitGrowth
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in getCharts: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [
                    'visitation_trend' => [],
                    'unit_growth' => $this->getMockUnitGrowthData()
                ]
            ];
        }
    }
}