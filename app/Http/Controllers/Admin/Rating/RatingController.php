<?php

namespace App\Http\Controllers\Admin\Rating;

use App\Http\Controllers\Controller;
use App\Services\Admin\Rating\RatingManagementService;
use App\Models\Feedback\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected RatingManagementService $service;

    public function __construct(RatingManagementService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Rating::class);
        
        try {
            $filters = $request->only(['search', 'status', 'unit_id', 'per_page', 'sort']);
            $ratings = $this->service->getFilteredRatings($filters);
            $stats = $this->service->getStats();
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.ratings.partials.rows', compact('ratings'))->render(),
                    'pagination' => view('admin.ratings.partials.pagination', ['paginator' => $ratings])->render()
                ]);
            }
            
            return view('admin.ratings.index', compact('ratings', 'stats'));
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.index')->with('error', 'Gagal memuat data');
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->service->getDetail($id);
            $this->authorize('view', $data['rating']);
            return view('admin.ratings.show', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.index')->with('error', 'Rating tidak ditemukan');
        }
    }

    public function moderate(Request $request, int $id)
    {
        $rating = Rating::findOrFail($id);
        $this->authorize('moderate', $rating);
        
        $request->validate([
            'action' => 'required|in:approve,reject,censor_comment',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->service->moderate($id, $request->action, $request->reason, auth('admin')->id());
            
            return response()->json([
                'success' => true,
                'message' => 'Rating berhasil dimoderasi'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memoderasi: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $rating = Rating::findOrFail($id);
        $this->authorize('update', $rating);
        
        $request->validate([
            'status' => 'required|in:active,edited,archived',
        ]);
        
        try {
            $this->service->updateStatus($id, $request->status);
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $this->authorize('moderate', Rating::class);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:ratings,id',
            'action' => 'required|in:approve,reject,archive,censor_comment',
            'reason' => 'nullable|string|max:1000',
        ]);
        
        try {
            $count = $this->service->bulkAction($request->ids, $request->action, $request->reason, auth('admin')->id());
            return response()->json(['success' => true, 'message' => "{$count} rating berhasil diproses"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $this->authorize('export', Rating::class);
        
        try {
            return $this->service->export($request->all());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function stats()
    {
        $this->authorize('viewAny', Rating::class);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getStats()
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat statistik'], 500);
        }
    }

    public function destroy(int $id)
    {
        $rating = Rating::findOrFail($id);
        $this->authorize('delete', $rating);
        
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'Rating berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}