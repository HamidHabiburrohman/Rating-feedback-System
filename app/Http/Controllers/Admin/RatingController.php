<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\RatingService;
use App\Models\Rating;
use App\Models\Unit;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'search', 'unit']);
        $ratings = $this->ratingService->getAllRatings($filters)
            ->paginate($request->per_page ?? 10)
            ->withQueryString();
        
        $units = Unit::where('status', 'aktif')->get(['id', 'nama_unit', 'kode_unit']);
        $stats = $this->ratingService->getRatingStats();
        $unitRanking = $this->ratingService->getUnitRanking(10);

        return view('admin.ratings.index', compact('ratings', 'units', 'stats', 'unitRanking'));
    }

    public function show($id)
    {
        $rating = Rating::with(['unit', 'visitorSession'])->findOrFail($id);
        $similarRatings = Rating::where('unit_id', $rating->unit_id)
            ->where('id', '!=', $id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.ratings.show', compact('rating', 'similarRatings'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,dibalas,selesai']);
        $this->ratingService->updateRating($id, ['status' => $request->status]);
        return back()->with('success', 'Status updated successfully');
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['reply_message' => 'required|string|max:1000']);
        $this->ratingService->respondToRating($id, [
            'message' => $request->reply_message,
            'replied_by' => auth()->id(),
            'replied_at' => now()->toDateTimeString()
        ]);
        return back()->with('success', 'Reply sent successfully');
    }

    public function destroy($id)
    {
        $this->ratingService->deleteRating($id);
        return redirect()->route('admin.ratings.index')->with('success', 'Rating deleted');
    }

    public function analytics()
    {
        $stats = $this->ratingService->getRatingStats();
        $unitRanking = $this->ratingService->getUnitRanking(10);
        $monthlyStats = $this->ratingService->getMonthlyStats();
        return view('admin.ratings.analytics', compact('stats', 'unitRanking', 'monthlyStats'));
    }
}