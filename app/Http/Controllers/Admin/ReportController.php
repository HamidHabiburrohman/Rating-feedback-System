<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReportManagementService;
use App\Models\Report\Report;
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
            $filters = $request->only(['search', 'status', 'priority', 'unit_id', 'per_page', 'sort']);
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
            return redirect()->route('admin.reports.index')
                ->with('error', 'Gagal memuat data laporan');
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->service->getDetail($id);
            $this->authorize('view', $data['report']);
            return view('admin.reports.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.index')
                ->with('error', 'Laporan tidak ditemukan');
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $report = Report::findOrFail($id);
        $this->authorize('update', $report);
        
        $request->validate([
            'status' => 'required|in:new,assigned,in_progress,replied,resolved,rejected,pending_preview',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->service->updateStatus($id, $request->status, $request->reason, auth('admin')->id());
            
            return response()->json([
                'success' => true,
                'message' => 'Status laporan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reply(Request $request, int $id)
    {
        $report = Report::findOrFail($id);
        $this->authorize('reply', $report);
        
        $request->validate([
            'reply' => 'required|string|min:3|max:5000',
            'is_public' => 'boolean',
        ]);
        
        try {
            $this->service->reply(
                $id,
                $request->reply,
                $request->boolean('is_public', true),
                auth('admin')->id()
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim balasan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $this->authorize('update', Report::class);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reports,id',
            'status' => 'required|in:new,assigned,in_progress,replied,resolved,rejected,pending_preview',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $count = $this->service->bulkUpdateStatus(
                $request->ids,
                $request->status,
                $request->reason,
                auth('admin')->id()
            );
            
            return response()->json([
                'success' => true,
                'message' => "{$count} laporan berhasil diperbarui"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui massal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $this->authorize('export', Report::class);
        
        try {
            return $this->service->export($request->all());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}