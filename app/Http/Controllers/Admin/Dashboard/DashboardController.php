<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Admin\Dashboard\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $stats = $this->service->getStats();
            return view('admin.dashboard.index', compact('stats'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    public function stats()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getStats()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    public function charts(Request $request)
    {
        try {
            $period = $request->get('period', 'week');
            return response()->json([
                'success' => true,
                'data' => $this->service->getChartData($period)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data chart'
            ], 500);
        }
    }

    public function topUnits(Request $request, string $type = 'all')
    {
        try {
            $limit = (int) $request->get('limit', 50);
            $units = $this->service->getTopUnits($type, $limit);
            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil top units'
            ], 500);
        }
    }

    public function attentionUnits(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 50);
            $units = $this->service->getAttentionUnits($limit);
            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil units yang perlu perhatian'
            ], 500);
        }
    }

    public function recentRated(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 4);
            return response()->json([
                'success' => true,
                'data' => $this->service->getRecentRated($limit)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data rating terbaru'
            ], 500);
        }
    }

    public function auditLogs(Request $request)
    {
        try {
            $logs = $this->service->getAuditLogs($request->all());
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil audit logs'
            ], 500);
        }
    }

    public function overview()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getOverview()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil overview'
            ], 500);
        }
    }

    public function topEmployees(Request $request)
    {
        try {
            $filter = $request->get('filter', 'all');
            $limit = (int) $request->get('limit', 5);
            $employees = $this->service->getTopEmployees($filter, $limit);
            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data employee'
            ], 500);
        }
    }
}
