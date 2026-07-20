<?php

namespace App\Http\Controllers\Employee\Rating;

use App\Http\Controllers\Controller;
use App\Services\Employee\AssignedUnitService;
use App\Services\Employee\RatingReplyService;
use App\Models\Feedback\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected AssignedUnitService $unitService;
    protected RatingReplyService $replyService;

    public function __construct(
        AssignedUnitService $unitService,
        RatingReplyService $replyService
    ) {
        $this->unitService = $unitService;
        $this->replyService = $replyService;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $filters = $request->only(['search', 'status', 'sort', 'per_page', 'unit_id']);
            
            // Jika unit_id tidak diberikan, ambil semua ratings dari semua assigned units
            if (empty($filters['unit_id'])) {
                $assignedUnits = $this->unitService->getAssignedUnits($employee->id);
                $unitIds = collect($assignedUnits)->pluck('unit_id')->toArray();
                
                $ratings = Rating::with(['student', 'unit', 'scores.category', 'replies.employee'])
                    ->whereIn('unit_id', $unitIds);
                
                if (!empty($filters['status'])) {
                    $ratings->where('status', $filters['status']);
                }
                
                if (!empty($filters['search'])) {
                    $search = $filters['search'];
                    $ratings->where(function ($q) use ($search) {
                        $q->where('comment', 'like', "%{$search}%")
                          ->orWhereHas('student', fn($s) => $s->where('name', 'like', "%{$search}%"))
                          ->orWhereHas('unit', fn($u) => $u->where('name', 'like', "%{$search}%"));
                    });
                }
                
                $sort = $filters['sort'] ?? 'latest';
                if ($sort === 'highest') {
                    $ratings->orderByDesc('overall_score');
                } elseif ($sort === 'lowest') {
                    $ratings->orderBy('overall_score');
                } else {
                    $ratings->latest();
                }
                
                $ratings = $ratings->paginate($filters['per_page'] ?? 10);
            } else {
                $ratings = $this->unitService->getUnitRatings($employee->id, $filters['unit_id'], $filters);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('employee.ratings.partials.rows', compact('ratings'))->render(),
                    'pagination' => view('employee.ratings.partials.pagination', ['paginator' => $ratings])->render()
                ]);
            }
            
            return view('employee.ratings.index', compact('ratings'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat rating: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('employee.units.index')
                ->with('error', 'Gagal memuat rating: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $rating = Rating::with([
                'student',
                'unit.unitType',
                'unit.unitDepartment',
                'scores.category',
                'replies.employee',
                'replies.admin'
            ])->findOrFail($id);
            
            // Verify authorization via unit service
            $this->unitService->getUnitDetail($employee->id, $rating->unit_id);
            
            return view('employee.ratings.show', compact('rating'));
        } catch (\Exception $e) {
            return redirect()->route('employee.ratings.index')
                ->with('error', $e->getMessage());
        }
    }

    public function storeReply(Request $request, int $ratingId)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'reply' => 'required|string|min:3|max:5000',
        ]);
        
        try {
            $reply = $this->replyService->reply($ratingId, $request->reply, $employee->id);
            
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

    public function getReplies(int $ratingId)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $replies = $this->replyService->getReplies($ratingId);
            
            return response()->json([
                'success' => true,
                'data' => $replies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}