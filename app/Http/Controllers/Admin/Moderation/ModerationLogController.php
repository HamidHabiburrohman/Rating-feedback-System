<?php

namespace App\Http\Controllers\Admin\Moderation;

use App\Http\Controllers\Controller;
use App\Models\System\ModerationLog;
use App\Services\Admin\ModerationLogService;
use Illuminate\Http\Request;

class ModerationLogController extends Controller
{
    protected ModerationLogService $service;

    public function __construct(ModerationLogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', ModerationLog::class);
        
        try {
            $filters = $request->only([
                'search', 'action', 'target_type', 'admin_id',
                'date_from', 'date_to', 'per_page', 'sort'
            ]);
            
            $logs = $this->service->getFilteredLogs($filters);
            $stats = $this->service->getStats();
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.moderation-logs.partials.rows', compact('logs'))->render(),
                    'pagination' => view('admin.moderation-logs.partials.pagination', ['paginator' => $logs])->render()
                ]);
            }
            
            return view('admin.moderation-logs.index', compact('logs', 'stats'));
        } catch (\Exception $e) {
            return redirect()->route('admin.moderation-logs.index')
                ->with('error', 'Gagal memuat log moderasi');
        }
    }

    public function show(int $id)
    {
        try {
            $log = $this->service->getDetail($id);
            $this->authorize('view', $log);
            return view('admin.moderation-logs.show', compact('log'));
        } catch (\Exception $e) {
            return redirect()->route('admin.moderation-logs.index')
                ->with('error', 'Log tidak ditemukan');
        }
    }

    public function stats()
    {
        $this->authorize('viewAny', ModerationLog::class);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getStats()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik'
            ], 500);
        }
    }

    public function byTarget(string $targetType, int $targetId)
    {
        $this->authorize('viewAny', ModerationLog::class);
        
        try {
            $logs = $this->service->getByTarget($targetType, $targetId);
            
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat log'
            ], 500);
        }
    }

    public function byAdmin(int $adminId)
    {
        $this->authorize('viewAny', ModerationLog::class);
        
        try {
            $logs = $this->service->getByAdmin($adminId);
            
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat log admin'
            ], 500);
        }
    }

    public function summary()
    {
        $this->authorize('viewAny', ModerationLog::class);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getSummary()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat ringkasan'
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $this->authorize('export', ModerationLog::class);
        
        try {
            return $this->service->export($request->all());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor log: ' . $e->getMessage());
        }
    }

    public function cleanup(Request $request)
    {
        $this->authorize('cleanup', ModerationLog::class);
        
        $request->validate([
            'days' => 'required|integer|min:30|max:365',
        ]);
        
        try {
            $count = $this->service->cleanup($request->days);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} log lama berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal cleanup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, ModerationLog $log)
    {
        $this->authorize('delete', $log);
        
        try {
            $this->service->delete($log->id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Log berhasil dihapus'
                ]);
            }
            
            return back()->with('success', 'Log berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus log: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $this->authorize('delete', ModerationLog::class);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:moderation_logs,id',
        ]);
        
        try {
            $count = $this->service->bulkDelete($request->ids);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} log berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus massal: ' . $e->getMessage()
            ], 500);
        }
    }
}