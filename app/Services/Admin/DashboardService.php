<?php

namespace App\Services\Admin;

use App\Models\Rating;
use App\Models\Report;
use App\Models\Unit;
use App\Models\UnitVisit;
use App\Models\VisitorSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardStats(): array
    {
        try {
            $today = Carbon::today();
            $weekStart = Carbon::now()->startOfWeek();
            $monthStart = Carbon::now()->startOfMonth();

            $averageRating = 0;
            try {
                $avgRatingResult = DB::selectOne("
                SELECT 
                    COALESCE(
                        AVG(
                            (
                                COALESCE(JSON_EXTRACT(metadata, '$.kebersihan'), 0) + 
                                COALESCE(JSON_EXTRACT(metadata, '$.pelayanan'), 0) + 
                                COALESCE(JSON_EXTRACT(metadata, '$.kecepatan'), 0) + 
                                COALESCE(JSON_EXTRACT(metadata, '$.keramahan'), 0) + 
                                COALESCE(JSON_EXTRACT(metadata, '$.fasilitas'), 0)
                            ) / 5.0
                        ), 0
                    ) as average_rating
                FROM ratings 
                WHERE metadata IS NOT NULL AND metadata != 'null'
            ");

                $averageRating = $avgRatingResult ? round((float) $avgRatingResult->average_rating, 1) : 0;
            } catch (\Exception $e) {
                Log::warning('Error calculating average rating: ' . $e->getMessage());
                $averageRating = 0;
            }

            $todayVisitors = VisitorSession::whereDate('created_at', $today)->count();
            $thisWeekVisitors = VisitorSession::whereBetween('created_at', [$weekStart, Carbon::now()])->count();

            return [
                'pengunjung' => [
                    'hari_ini' => $todayVisitors,
                    'minggu_ini' => $thisWeekVisitors,
                    'bulan_ini' => UnitVisit::where('tanggal', '>=', $monthStart)->count(),
                    'total' => UnitVisit::count()
                ],
                'rating' => [
                    'hari_ini' => Rating::whereDate('created_at', $today)->count(),
                    'minggu_ini' => Rating::where('created_at', '>=', $weekStart)->count(),
                    'bulan_ini' => Rating::where('created_at', '>=', $monthStart)->count(),
                    'total' => Rating::count(),
                    'rata_rata' => $averageRating
                ],
                'laporan' => [
                    'baru' => Report::where('status', 'baru')->count(),
                    'diproses' => Report::where('status', 'diproses')->count(),
                    'selesai' => Report::where('status', 'selesai')->count(),
                    'total' => Report::count()
                ],
                'unit' => [
                    'total' => Unit::count(),
                    'aktif' => Unit::where('status_aktif', true)->count(),
                    'non_aktif' => Unit::where('status_aktif', false)->count(),
                    'per_jenis' => $this->getUnitsByType()
                ],
                'admin' => [
                    'total' => User::count(),
                    'super_admin' => User::where('role', 'super_admin')->count(),
                    'admin' => User::where('role', 'admin')->count()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in getDashboardStats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function getUnitsByType()
    {
        try {
            return Unit::groupBy('jenis_unit')
                ->selectRaw('jenis_unit, count(*) as total')
                ->get()
                ->map(function ($item) {
                    return [
                        'jenis_unit' => $item->jenis_unit,
                        'total' => $item->total
                    ];
                });
        } catch (\Exception $e) {
            Log::warning('Error getting units by type: ' . $e->getMessage());
            return collect();
        }
    }

    private function getDefaultStats(): array
    {
        return [
            'pengunjung' => [
                'hari_ini' => 0,
                'minggu_ini' => 0,
                'bulan_ini' => 0,
                'total' => 0
            ],
            'rating' => [
                'hari_ini' => 0,
                'minggu_ini' => 0,
                'bulan_ini' => 0,
                'total' => 0,
                'rata_rata' => 0
            ],
            'laporan' => [
                'baru' => 0,
                'diproses' => 0,
                'selesai' => 0,
                'total' => 0
            ],
            'unit' => [
                'total' => 0,
                'aktif' => 0,
                'non_aktif' => 0,
                'per_jenis' => []
            ],
            'admin' => [
                'total' => 0,
                'super_admin' => 0,
                'admin' => 0
            ]
        ];
    }

    public function getChartData(): array
    {
        try {
            $type = request()->get('type', 'daily');
            
            $visitationData = $type === 'monthly' 
                ? $this->getMonthlyVisitorsData()
                : $this->getDailyVisitorsData();
            
            $ratingData = $this->getRatingDistribution();

            return [
                'visitation_trend' => $visitationData,
                'rating_distribution' => $ratingData,
            ];
        } catch (\Exception $e) {
            Log::error('Error in getChartData: ' . $e->getMessage());
            return [
                'visitation_trend' => [],
                'rating_distribution' => [
                    'labels' => ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                    'data' => [0, 0, 0, 0, 0]
                ]
            ];
        }
    }

    private function getDailyVisitorsData(): array
    {
        $data = [];
        $startDate = Carbon::now()->subDays(30);
        
        $visitorsData = VisitorSession::selectRaw('DATE(created_at) as date, COUNT(*) as visitors')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $currentDate = $startDate->copy();
        while ($currentDate <= Carbon::now()) {
            $dateString = $currentDate->format('Y-m-d');
            $visitorData = $visitorsData->get($dateString);
            
            $data[] = [
                'date' => $dateString,
                'day' => $currentDate->format('D'),
                'day_number' => $currentDate->format('d'),
                'visitors' => $visitorData ? $visitorData->visitors : 0
            ];
            
            $currentDate->addDay();
        }
        
        return $data;
    }
    
    private function getMonthlyVisitorsData(): array
    {
        $currentYear = Carbon::now()->year;
        $data = [];
        
        $monthlyVisitors = VisitorSession::selectRaw('MONTH(created_at) as month, COUNT(*) as visitors')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];
        
        for ($month = 1; $month <= 12; $month++) {
            $visitorData = $monthlyVisitors->firstWhere('month', $month);
            $data[] = [
                'month' => $monthNames[$month],
                'visitors' => $visitorData ? $visitorData->visitors : 0,
                'date' => Carbon::create($currentYear, $month, 1)->format('Y-m-d')
            ];
        }
        
        return $data;
    }

    private function getRatingDistribution(): array
    {
        $distribution = DB::select("
            SELECT 
                ROUND(
                    (
                        COALESCE((metadata->>'$.kebersihan'), 0) + 
                        COALESCE((metadata->>'$.pelayanan'), 0) + 
                        COALESCE((metadata->>'$.kecepatan'), 0) + 
                        COALESCE((metadata->>'$.keramahan'), 0) + 
                        COALESCE((metadata->>'$.fasilitas'), 0)
                    ) / 5.0
                ) as rounded_rating,
                COUNT(*) as count
            FROM ratings 
            WHERE metadata IS NOT NULL AND metadata != 'null'
            GROUP BY rounded_rating
            ORDER BY rounded_rating
        ");

        $distArray = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0];

        foreach ($distribution as $item) {
            $rating = (int) $item->rounded_rating;
            if ($rating >= 1 && $rating <= 5) {
                $distArray[$rating] = (int) $item->count;
            }
        }

        return [
            'labels' => ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            'data' => array_values($distArray)
        ];
    }

    public function getOverviewData(): array
    {
        try {
            $topUnits = $this->getTopUnitRate('popularity');

            return [
                'recent_ratings' => Rating::with(['unit'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(),
                'pending_reports' => Report::with(['unit', 'admin'])
                    ->where('status', 'baru')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(),
                'active_visits' => UnitVisit::with('unit')
                    ->whereNull('waktu_keluar')
                    ->where('waktu_masuk', '>=', Carbon::now()->subHours(2))
                    ->orderBy('waktu_masuk', 'desc')
                    ->limit(10)
                    ->get(),
                'top_rated_units' => $topUnits
            ];
        } catch (\Exception $e) {
            Log::error('Error in getOverviewData: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTopUnitRate($type = 'popularity'): array
    {
        try {
            $orderBy = $type === 'quality'
                ? 'ORDER BY average_rating DESC, total_rating DESC'
                : 'ORDER BY total_rating DESC, average_rating DESC';

            $units = DB::select("
                SELECT 
                    units.id,
                    units.nama_unit,
                    units.foto_unit,
                    units.status_aktif,
                    COUNT(ratings.id) as total_rating,
                    COALESCE(
                        AVG(
                            (
                                COALESCE((ratings.metadata->>'$.kebersihan'), 0) + 
                                COALESCE((ratings.metadata->>'$.pelayanan'), 0) + 
                                COALESCE((ratings.metadata->>'$.kecepatan'), 0) + 
                                COALESCE((ratings.metadata->>'$.keramahan'), 0) + 
                                COALESCE((ratings.metadata->>'$.fasilitas'), 0)
                            ) / 5.0
                        ), 0
                    ) as average_rating
                FROM units
                LEFT JOIN ratings ON units.id = ratings.unit_id 
                    AND ratings.metadata IS NOT NULL 
                    AND ratings.metadata != 'null'
                GROUP BY units.id, units.nama_unit, units.foto_unit, units.status_aktif
                HAVING COUNT(ratings.id) > 0
                {$orderBy}
                LIMIT 5
            ");

            $topUnits = [];

            foreach ($units as $unit) {
                $averageRating = (float) $unit->average_rating;
                $totalRating = (int) $unit->total_rating;

                $fotoUrl = $this->getFotoUrl($unit->foto_unit);

                $topUnits[] = [
                    'id' => $unit->id,
                    'nama_unit' => $unit->nama_unit,
                    'foto_unit' => $fotoUrl,
                    'rata_rata_rating' => round($averageRating, 1),
                    'total_rating' => $totalRating,
                    'status' => $unit->status_aktif ? 'Aktif' : 'Non-Aktif',
                    'badge_class' => $unit->status_aktif ? 'bg-light-success text-success' : 'bg-light-danger text-danger'
                ];
            }

            if (empty($topUnits)) {
                $activeUnits = Unit::where('status_aktif', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(['id', 'nama_unit', 'foto_unit', 'status_aktif']);

                foreach ($activeUnits as $unit) {
                    $fotoUrl = $this->getFotoUrl($unit->foto_unit);

                    $topUnits[] = [
                        'id' => $unit->id,
                        'nama_unit' => $unit->nama_unit,
                        'foto_unit' => $fotoUrl,
                        'rata_rata_rating' => 0,
                        'total_rating' => 0,
                        'status' => 'Aktif',
                        'badge_class' => 'bg-light-success text-success'
                    ];
                }
            }

            return $topUnits;
        } catch (\Exception $e) {
            Log::error('Error in getTopUnitRate: ' . $e->getMessage());
            return [];
        }
    }

    private function getFotoUrl($fotoPath): ?string
    {
        if (!$fotoPath) {
            return null;
        }

        if (filter_var($fotoPath, FILTER_VALIDATE_URL)) {
            return $fotoPath;
        }

        if (strpos($fotoPath, 'storage/') === 0) {
            $relativePath = str_replace('storage/', '', $fotoPath);
            if (file_exists(storage_path('app/public/' . $relativePath))) {
                return asset('storage/' . $relativePath);
            }
        }

        if (file_exists(public_path($fotoPath))) {
            return asset($fotoPath);
        }

        if (file_exists(public_path('storage/' . $fotoPath))) {
            return asset('storage/' . $fotoPath);
        }

        return null;
    }
}