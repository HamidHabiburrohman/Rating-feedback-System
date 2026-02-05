<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReportRequest;
use App\Http\Requests\Admin\UpdateReportRequest;
use App\Models\Report;
use App\Models\Unit;
use App\Services\Admin\ReportService;
use App\Services\ExportService;
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
        $stats = $this->reportService->getStats();
        $units = $this->reportService->getUnitsForFilter();

        return view('admin.reports.index', compact('reports', 'stats', 'units'));
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

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $this->reportService->deleteReport($report);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        $filters['per_page'] = 'all';

        $reports = $this->reportService->getReports($filters);

        $columns = [
            'id' => 'ID',
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'tipe_label' => 'Tipe',
            'prioritas_label' => 'Prioritas',
            'status_label' => 'Status',
            'unit.nama_unit' => 'Unit',
            'created_at' => 'Tanggal Dibuat'
        ];

        $options = [
            'title' => 'Laporan',
            'filename' => 'laporan-' . date('Ymd'),
            'orientation' => 'landscape',
            'summary' => [
                'Total Data' => $reports->count(),
                'Tanggal Export' => now()->format('d/m/Y H:i')
            ]
        ];

        $format = $request->get('format', 'excel');
        return app(ExportService::class)->export($reports, $columns, $options, $format);
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