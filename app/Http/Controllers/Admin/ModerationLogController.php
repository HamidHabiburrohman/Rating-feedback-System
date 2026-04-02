<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ModerationLog\ModerationLogFilterRequest;
use App\Http\Requests\Admin\ModerationLog\ExportLogsRequest;
use App\Services\Admin\ModerationLogService;
use App\Models\ModerationLog;
use Illuminate\Http\Request;

class ModerationLogController extends Controller
{
    protected ModerationLogService $service;

    public function __construct(ModerationLogService $service)
    {
        $this->service = $service;
    }

    public function index(ModerationLogFilterRequest $request)
    {
        $filters = $request->validated();
        $logs = $this->service->getPaginatedLogs($filters);
        $filterData = $this->service->getFilterData();
        $stats = $this->service->getStats();

        return view('admin.moderation-logs.index', compact('logs', 'filterData', 'stats'));
    }

    public function show($id)
    {
        try {
            return view('admin.moderation-logs.show', ['log' => $this->service->getLogDetail($id)]);
        } catch (\Exception $e) {
            return redirect()->route('admin.moderation-logs.index')->with('error', 'Log tidak ditemukan');
        }
    }

    public function stats(Request $request)
    {
        try {
            $stats = $this->service->getStats();
            return $request->wantsJson()
                ? response()->json(['success' => true, 'data' => $stats])
                : view('admin.moderation-logs.stats', compact('stats'));
        } catch (\Exception $e) {
            $message = 'Gagal mengambil statistik';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 500)
                : back()->with('error', $message);
        }
    }

    public function byTarget(Request $request, string $targetType, int $targetId)
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getLogsByTarget($targetType, $targetId)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil log: ' . $e->getMessage()], 500);
        }
    }

    public function export(ExportLogsRequest $request)
    {
        try {
            return $this->service->export($request->validated());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function cleanup(Request $request)
    {
        try {
            $count = $this->service->cleanupOldLogs($request->get('days', 90));
            return response()->json(['success' => true, 'message' => "{$count} log lama telah dibersihkan", 'count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membersihkan log: ' . $e->getMessage()], 500);
        }
    }

    public function summary(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getSummaryByDateRange(
                    $request->get('start_date', now()->subDays(30)->format('Y-m-d')),
                    $request->get('end_date', now()->format('Y-m-d'))
                )
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil ringkasan log'], 500);
        }
    }

    public function byAdmin(Request $request, $adminId)
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getActionsByAdmin($adminId, $request->get('limit', 50))]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil log admin'], 500);
        }
    }

    public function destroy(ModerationLog $log)
    {
        try {
            $log->delete();
            return redirect()->route('admin.moderation-logs.index')->with('success', 'Log berhasil dihapus');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus log: ' . $e->getMessage()], 500);
        }
    }

    public function bulkDestroy(Request $request)
    {
        try {
            $request->validate(['log_ids' => 'required|array', 'log_ids.*' => 'exists:moderation_logs,id']);
            $count = ModerationLog::whereIn('id', $request->log_ids)->delete();
            return redirect()->route('admin.moderation-logs.index')->with('success', "{$count} log berhasil dihapus");
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus log: ' . $e->getMessage()], 500);
        }
    }
}