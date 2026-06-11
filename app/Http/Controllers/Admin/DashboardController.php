<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
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
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
        try {
            $stats = $this->service->getStats();
            $recentRatings = $this->service->getRecentRatings(5);
            $recentReports = $this->service->getRecentReports(5);
            
            return view('admin.dashboard', compact('stats', 'recentRatings', 'recentReports'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    public function stats()
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getStats()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    public function charts(Request $request)
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
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

    public function overview()
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
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

    public function auditLogs(Request $request)
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
        try {
            $logs = $this->service->getAuditLogs($request->all());
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.dashboard.partials.audit-logs', compact('logs'))->render(),
                    'pagination' => view('admin.dashboard.partials.pagination', ['paginator' => $logs])->render()
                ]);
            }
            
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

    public function recentRated(Request $request)
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
        try {
            $limit = $request->get('limit', 10);
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

    public function topUnits(Request $request, string $type = 'all')
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
        try {
            $limit = $request->get('limit', 5);
            return response()->json([
                'success' => true,
                'data' => $this->service->getTopUnits($type, $limit)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil top units'
            ], 500);
        }
    }

    public function attentionUnits()
    {
        $this->authorize('viewDashboard', \App\Models\Authentication\Admin::class);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getAttentionUnits()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil units yang perlu perhatian'
            ], 500);
        }
    }
}