<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\ReportFilterRequest;
use App\Http\Requests\Admin\Report\UpdateReportStatusRequest;
use App\Http\Requests\Admin\Report\BulkReportActionRequest;
use App\Http\Requests\Admin\Report\ReplyReportRequest;
use App\Http\Requests\Admin\Report\UpdateReportRequest;
use App\Services\Admin\ReportManagementService;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected ReportManagementService $service;

    public function __construct(ReportManagementService $service)
    {
        $this->service = $service;
    }

    public function index(ReportFilterRequest $request)
    {
        $filters = $request->validated();
        $reports = $this->service->getPaginatedReports($filters);
        $filterData = $this->service->getFilterData();
        $stats = $this->service->getStats();

        return view('admin.reports.index', compact('reports', 'filterData', 'stats'));
    }

    public function show($id)
    {
        try {
            $report = $this->service->findReport($id);
            return view('admin.reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.index')->with('error', 'Laporan tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $report = $this->service->findReport($id);
            return view('admin.reports.edit', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.index')->with('error', 'Laporan tidak ditemukan');
        }
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        try {
            $this->service->updateReport($report->id, $request->validated(), Auth::id());

            return redirect()->route('admin.reports.show', $report->id)
                ->with('success', 'Laporan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateStatus(UpdateReportStatusRequest $request, Report $report)
    {
        try {
            $this->service->updateStatus($report->id, $request->validated(), Auth::id());

            return redirect()->route('admin.reports.show', $report->id)
                ->with('success', 'Status laporan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function bulkAction(BulkReportActionRequest $request)
    {
        try {
            $count = $this->service->bulkUpdateStatus(
                $request->report_ids,
                $request->action,
                $request->admin_response ?? '',
                Auth::id()
            );

            return redirect()->route('admin.reports.index')
                ->with('success', $count . ' laporan berhasil diproses');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses laporan: ' . $e->getMessage());
        }
    }

    public function stats(Request $request)
    {
        try {
            $data = [
                'overall' => $this->service->getStats(),
                'monthly' => $this->service->getMonthlyStats()
            ];

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $data]);
            }

            return view('admin.reports.stats', $data);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
            }
            return redirect()->back()->with('error', 'Gagal mengambil statistik: ' . $e->getMessage());
        }
    }

    public function unitStats($unitId)
    {
        try {
            $reports = $this->service->getPaginatedReports(['unit_id' => $unitId]);
            return view('admin.reports.unit-stats', compact('reports', 'unitId'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil statistik unit: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            return $this->service->export($request->only(['status', 'priority', 'unit_id', 'date_from', 'date_to']));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function destroy(Report $report)
    {
        try {
            $this->service->deleteReport($report->id);

            return redirect()->route('admin.reports.index')
                ->with('success', 'Laporan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    public function reply(ReplyReportRequest $request, Report $report)
    {
        try {
            $status = $request->status ?? 'replied';

            $this->service->reply(
                $report->id,
                $request->tanggapan_admin,
                Auth::id(),
                $request->ip(),
                $request->userAgent(),
                $status
            );

            return redirect()->route('admin.reports.show', $report->id)
                ->with('success', 'Tanggapan berhasil dikirim');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengirim tanggapan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
