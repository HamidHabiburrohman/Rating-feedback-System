<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function stats(): JsonResponse
    {
        try {
            $stats = $this->dashboardService->getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Return default stats jika error
            return response()->json([
                'success' => true,
                'data' => [
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
                ],
            ]);
        }
    }

    public function charts(): JsonResponse
    {
        try {
            $charts = $this->dashboardService->getChartData();

            return response()->json([
                'success' => true,
                'data' => $charts,
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard charts error: ' . $e->getMessage());

            // Return empty data jika error
            return response()->json([
                'success' => true,
                'data' => [
                    'visitation_trend' => [],
                    'rating_distribution' => [
                        'labels' => ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                        'data' => [0, 0, 0, 0, 0]
                    ]
                ]
            ]);
        }
    }

    public function overview(): JsonResponse
    {
        try {
            $overview = $this->dashboardService->getOverviewData();

            return response()->json([
                'success' => true,
                'data' => $overview,
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard overview error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load overview data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function topUnitsByType($type): JsonResponse
    {
        try {
            $topUnits = $this->dashboardService->getTopUnitRate($type);

            return response()->json([
                'success' => true,
                'data' => $topUnits,
                'type' => $type
            ]);
        } catch (\Exception $e) {
            Log::error('Top units by type error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load top units',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}