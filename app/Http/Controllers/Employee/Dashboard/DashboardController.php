<?php

namespace App\Http\Controllers\Employee\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Employee\DashboardService;
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
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $stats = $this->service->getStats($employee->id);
            $recentRatings = $this->service->getRecentRatings($employee->id, 5);
            $recentReports = $this->service->getRecentReports($employee->id, 5);
            $assignedUnits = $this->service->getAssignedUnits($employee->id);
            
            return view('employee.dashboard', compact('stats', 'recentRatings', 'recentReports', 'assignedUnits'));
        } catch (\Exception $e) {
            return redirect()->route('employee.dashboard.index')
                ->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    public function stats()
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getStats($employee->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }

    public function recentRatings(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        $limit = $request->get('limit', 5);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getRecentRatings($employee->id, $limit)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data rating terbaru'
            ], 500);
        }
    }

    public function recentReports(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        $limit = $request->get('limit', 5);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getRecentReports($employee->id, $limit)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data report terbaru'
            ], 500);
        }
    }
}