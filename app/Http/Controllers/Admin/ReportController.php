<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReportRequest;
use App\Http\Requests\Admin\UpdateReportRequest;
use App\Models\Report;
use App\Models\Unit;
use App\Services\Admin\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $reports = $this->reportService->getReports($request->all(), $perPage);

        // Debug: Cek pagination
        \Log::info('Reports Pagination Debug', [
            'count' => $reports->count(),
            'total' => $reports->total(),
            'perPage' => $reports->perPage(),
            'currentPage' => $reports->currentPage(),
            'items_count' => count($reports->items()),
            'request_per_page' => $perPage,
            'url' => $request->fullUrl()
        ]);

        // Debug langsung di browser (temporary)
        if ($request->has('debug')) {
            dd([
                'pagination_info' => [
                    'count' => $reports->count(),
                    'total' => $reports->total(),
                    'perPage' => $reports->perPage(),
                    'currentPage' => $reports->currentPage(),
                    'lastPage' => $reports->lastPage(),
                ],
                'first_few_items' => $reports->take(3)->pluck('id', 'judul'),
                'request_params' => $request->all()
            ]);
        }

        $stats = $this->reportService->getStats();
        $units = $this->reportService->getUnitsForFilter();

        return view('admin.reports.index', [
            'reports' => $reports,
            'stats' => $stats,
            'units' => $units,
            'filters' => $request->only(['search', 'status', 'tipe', 'prioritas', 'unit', 'date_from', 'date_to'])
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['unit', 'admin']);

        return view('admin.reports.show', [
            'report' => $report
        ]);
    }

    public function create()
    {
        return view('admin.reports.create');
    }

    public function store(StoreReportRequest $request)
    {
        $this->reportService->createReport($request->validated());

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dibuat.');
    }

    public function edit(Report $report)
    {
        return view('admin.reports.edit', [
            'report' => $report
        ]);
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $this->reportService->updateReport($report, $request->validated());

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'tanggapan_admin' => 'nullable|string'
        ]);

        $this->reportService->updateStatus(
            $report,
            $validated['status'],
            auth()->id(),
            $validated['tanggapan_admin'] ?? null
        );

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        $this->reportService->deleteReport($report);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $filters = $request->only(['search', 'status', 'tipe', 'prioritas', 'unit', 'date_from', 'date_to']);
        $format = $request->input('format', 'excel');

        $reports = Report::with(['unit', 'admin'])
            ->search($filters['search'] ?? null)
            ->filterByStatus($filters['status'] ?? null)
            ->filterByTipe($filters['tipe'] ?? null)
            ->filterByPrioritas($filters['prioritas'] ?? null)
            ->filterByUnit($filters['unit'] ?? null)
            ->filterByDate($filters['date_from'] ?? null, $filters['date_to'] ?? null)
            ->latest()
            ->get();

        return $this->exportReports($reports, $format);
    }

    private function exportReports($reports, $format)
    {
        if ($format === 'pdf') {
            return response()->streamDownload(function () use ($reports) {
                echo "PDF Export for " . $reports->count() . " reports";
            }, 'reports-' . date('Y-m-d') . '.pdf');
        }

        return response()->streamDownload(function () use ($reports) {
            $headers = ['ID', 'Judul', 'Tipe', 'Prioritas', 'Status', 'Unit', 'Tanggal'];
            echo implode(',', $headers) . "\n";

            foreach ($reports as $report) {
                $row = [
                    $report->id,
                    $report->judul,
                    $report->tipe_label,
                    $report->prioritas,
                    $report->status_label,
                    $report->unit->nama_unit ?? '-',
                    $report->created_at->format('Y-m-d H:i')
                ];
                echo implode(',', $row) . "\n";
            }
        }, 'reports-' . date('Y-m-d') . '.csv');
    }
}