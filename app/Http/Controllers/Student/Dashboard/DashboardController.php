<?php

namespace App\Http\Controllers\Student\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Student\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            $studentId = auth('student')->id();

            $stats = $this->service->getStats($studentId);
            $recentRatings = $this->service->getRecentRatings($studentId, 5);
            $recentReports = $this->service->getRecentReports($studentId, 5);
            $recommendedUnits = $this->service->getRecommendedUnits($studentId, 6);

            return view('student.dashboard.index', compact(
                'stats',
                'recentRatings',
                'recentReports',
                'recommendedUnits'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage(), [
                'student_id' => auth('student')->id(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('student.dashboard.index')->with('error', 'Gagal memuat dashboard');
        }
    }
}
