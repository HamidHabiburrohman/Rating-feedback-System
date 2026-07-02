<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report\Report;
use App\Services\Admin\ReportManagementService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportManagementService $service;

    public function __construct(ReportManagementService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Report::class);

        try {
            $filters = $request->only([
                'search', 'status', 'priority', 'unit_id', 
                'date_from', 'date_to', 'sort', 'per_page'
            ]);
            
            $reports = $this->service->getFilteredReports($filters);
            $stats = $this->service->getStats();

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.reports.partials.rows', compact('reports'))->render(),
                    'pagination' => view('admin.reports.partials.pagination', ['paginator' => $reports])->render()
                ]);
            }

            return view('admin.reports.index', compact('reports', 'stats'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.dashboard')->with('error', 'Gagal memuat data laporan.');
        }
    }

    public function show(int $id)
    {
        $this->authorize('view', Report::class);

        try {
            $data = $this->service->getDetail($id);
            return view('admin.reports.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.index')->with('error', 'Laporan tidak ditemukan.');
        }
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Report::class);

        try {
            $filters = $request->only(['status', 'date_from', 'date_to']);
            return $this->service->export($filters);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data laporan.');
        }
    }
}