<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rating\RatingFilterRequest;
use App\Http\Requests\Admin\Rating\UpdateRatingStatusRequest;
use App\Http\Requests\Admin\Rating\ModerateRatingRequest;
use App\Http\Requests\Admin\Rating\BulkRatingActionRequest;
use App\Services\Admin\RatingManagementService;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected RatingManagementService $service;

    public function __construct(RatingManagementService $service)
    {
        $this->service = $service;
    }

    public function index(RatingFilterRequest $request)
    {
        $filters = $request->validated();
        $ratings = $this->service->getPaginatedRatings($filters);
        $filterData = $this->service->getFilterData();
        $stats = $this->service->getRatingStats();

        return view('admin.ratings.index', compact('ratings', 'filterData', 'stats'));
    }

    public function show($id)
    {
        try {
            $rating = $this->service->getRatingDetail($id);
            return view('admin.ratings.show', compact('rating'));
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.index')
                ->with('error', 'Rating tidak ditemukan');
        }
    }

    public function updateStatus(UpdateRatingStatusRequest $request, Rating $rating)
    {
        try {
            $this->service->updateRating($rating->id, ['status' => $request->status]);

            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('success', 'Status rating berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function moderate(ModerateRatingRequest $request, Rating $rating)
    {
        try {
            $this->service->moderate($rating->id, $request->action, $request->reason);

            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('success', 'Moderasi berhasil dilakukan');
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('error', 'Gagal melakukan moderasi: ' . $e->getMessage());
        }
    }

    public function bulkAction(BulkRatingActionRequest $request)
    {
        try {
            $count = $this->service->bulkAction($request->rating_ids, $request->action);

            return response()->json([
                'success' => true,
                'message' => $count . ' rating berhasil diproses'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses rating: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats(Request $request)
    {
        try {
            $data = [
                'overall' => $this->service->getRatingStats($request->get('unit_id')),
                'monthly' => $this->service->getMonthlyStats(),
                'ranking' => $this->service->getUnitRanking(10)
            ];

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'data' => $data]);
            }

            return view('admin.ratings.stats', $data);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
            }
            return redirect()->route('admin.ratings.index')
                ->with('error', 'Gagal mengambil statistik: ' . $e->getMessage());
        }
    }

    public function unitStats($unitId)
    {
        try {
            $stats = $this->service->getRatingStats($unitId);
            $ratings = $this->service->getAllRatings(['unit_id' => $unitId])->paginate(15);

            return view('admin.ratings.unit-stats', compact('stats', 'ratings', 'unitId'));
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.index')
                ->with('error', 'Gagal mengambil statistik unit: ' . $e->getMessage());
        }
    }

    public function destroy(Rating $rating)
    {
        try {
            $this->service->deleteRating($rating->id);

            return redirect()->route('admin.ratings.index')
                ->with('success', 'Rating berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('error', 'Gagal menghapus rating: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            return $this->service->export($request->only([
                'unit_id',
                'status',
                'date_from',
                'date_to',
                'min_score',
                'max_score'
            ]));
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.index')
                ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function getAdminName(Rating $rating)
    {
        return response()->json([
            'admin_name' => $rating->adminName
        ]);
    }
}