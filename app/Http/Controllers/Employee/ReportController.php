<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\AssignedUnitService;
use App\Services\Employee\ReportReplyService;
use App\Services\Employee\ReportStatusService;
use App\Models\Report\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected AssignedUnitService $unitService;
    protected ReportReplyService $replyService;
    protected ReportStatusService $statusService;

    public function __construct(
        AssignedUnitService $unitService,
        ReportReplyService $replyService,
        ReportStatusService $statusService
    ) {
        $this->unitService = $unitService;
        $this->replyService = $replyService;
        $this->statusService = $statusService;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $filters = $request->only(['search', 'status', 'priority', 'sort', 'per_page', 'unit_id']);
            
            // Jika unit_id tidak diberikan, ambil semua reports dari semua assigned units
            if (empty($filters['unit_id'])) {
                $assignedUnits = $this->unitService->getAssignedUnits($employee->id);
                $unitIds = collect($assignedUnits)->pluck('unit_id')->toArray();
                
                $reports = Report::with(['student', 'unit', 'category'])
                    ->whereIn('unit_id', $unitIds);
                
                if (!empty($filters['status'])) {
                    $reports->where('status', $filters['status']);
                }
                
                if (!empty($filters['priority'])) {
                    $reports->where('priority', $filters['priority']);
                }
                
                if (!empty($filters['search'])) {
                    $search = $filters['search'];
                    $reports->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%")
                          ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"))
                          ->orWhereHas('unit', fn($u) => $u->where('name', 'like', "%{$search}%"));
                    });
                }
                
                $sort = $filters['sort'] ?? 'latest';
                if ($sort === 'oldest') {
                    $reports->oldest();
                } elseif ($sort === 'priority') {
                    $reports->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')");
                } else {
                    $reports->latest();
                }
                
                $reports = $reports->paginate($filters['per_page'] ?? 10);
            } else {
                $reports = $this->unitService->getUnitReports($employee->id, $filters['unit_id'], $filters);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('employee.reports.partials.rows', compact('reports'))->render(),
                    'pagination' => view('employee.reports.partials.pagination', ['paginator' => $reports])->render()
                ]);
            }
            
            return view('employee.reports.index', compact('reports'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat report: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('employee.dashboard.index')
                ->with('error', 'Gagal memuat report: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $report = Report::with([
                'student',
                'unit.unitType',
                'unit.unitDepartment',
                'category',
                'rating',
                'attachments',
                'replies.employee',
                'replies.admin',
                'statusHistory.employee',
                'statusHistory.admin'
            ])->findOrFail($id);
            
            // Verify authorization via unit service
            $this->unitService->getUnitDetail($employee->id, $report->unit_id);
            
            return view('employee.reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('employee.reports.index')
                ->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'status' => 'required|in:in_progress,replied,resolved',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->statusService->updateStatus(
                $id,
                $request->status,
                $request->reason,
                $employee->id
            );
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diperbarui'
                ]);
            }
            
            return back()->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 403);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    public function resolve(Request $request, int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->statusService->resolve($id, $request->reason, $employee->id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diselesaikan'
                ]);
            }
            
            return back()->with('success', 'Laporan berhasil diselesaikan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 403);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    public function reopen(Request $request, int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->statusService->reopen($id, $request->reason, $employee->id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dibuka kembali'
                ]);
            }
            
            return back()->with('success', 'Laporan berhasil dibuka kembali');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 403);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    public function storeReply(Request $request, int $reportId)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'reply' => 'required|string|min:3|max:5000',
        ]);
        
        try {
            $reply = $this->replyService->reply($reportId, $request->reply, $employee->id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Balasan berhasil dikirim',
                    'data' => $reply->load('employee')
                ]);
            }
            
            return back()->with('success', 'Balasan berhasil dikirim');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 403);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateReply(Request $request, int $replyId)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'reply' => 'required|string|min:3|max:5000',
        ]);
        
        try {
            $reply = $this->replyService->updateReply($replyId, $request->reply, $employee->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil diperbarui',
                'data' => $reply
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function destroyReply(Request $request, int $replyId)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $this->replyService->deleteReply($replyId, $employee->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function history(int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $report = Report::findOrFail($id);
            
            // Verify authorization
            $this->unitService->getUnitDetail($employee->id, $report->unit_id);
            
            $history = $this->statusService->getHistory($id);
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}