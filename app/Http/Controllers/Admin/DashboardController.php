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
        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'routes' => [
                'overview' => route('admin.dashboard.overview'),
                'stats' => route('admin.dashboard.stats'),
                'charts' => route('admin.dashboard.charts'),
                'auditLogs' => route('admin.dashboard.audit-logs'),
                'recentRated' => route('admin.dashboard.recent-rated'),
                'topUnits' => route('admin.dashboard.top-units', ['type' => 'all'])
            ]
        ]);
    }

    public function stats()
    {
        return response()->json($this->service->getStats());
    }

    public function charts()
    {
        return response()->json($this->service->getCharts());
    }

    public function overview()
    {
        $stats = $this->service->getStats();
        $charts = $this->service->getCharts();
        $topUnits = $this->service->getTopUnits('all');

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats['data'] ?? [],
                'charts' => $charts['data'] ?? [],
                'top_rated_units' => $topUnits['data'] ?? []
            ]
        ]);
    }

    public function auditLogs()
    {
        return response()->json($this->service->getAuditLogs());
    }

    public function recentRated()
    {
        return response()->json($this->service->getRecentRated());
    }

    public function topUnits(Request $request, string $type = 'all')
    {
        return response()->json($this->service->getTopUnits($type));
    }

    public function attentionUnits()
    {
        return response()->json($this->service->getAttentionUnits());
    }

    public function getUnitsByFilter(Request $request, string $filter = 'all')
    {
        return response()->json($this->service->getTopUnits($filter));
    }
}