<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Unit;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::with('unit')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('komentar', 'LIKE', "%{$search}%")
                  ->orWhereHas('unit', function($q) use ($search) {
                      $q->where('nama_unit', 'LIKE', "%{$search}%")
                        ->orWhere('kode_unit', 'LIKE', "%{$search}%");
                  });
            });
        }

        $perPage = $request->per_page ?? 10;
        $ratings = $query->paginate($perPage)->withQueryString();

        $units = Unit::active()->get();
        $stats = $this->getStatsData();
        $unitRanking = $this->getUnitRankingData(10);

        return view('admin.ratings.index', compact(
            'ratings',
            'units',
            'stats',
            'unitRanking'
        ));
    }

    public function show($id)
    {
        $rating = Rating::with('unit')->findOrFail($id);

        $similarRatings = Rating::where('unit_id', $rating->unit_id)
            ->where('id', '!=', $rating->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.ratings.show', compact('rating', 'similarRatings'));
    }

    public function edit($id)
    {
        $rating = Rating::findOrFail($id);
        $units = Unit::active()->get();

        $statusOptions = [
            'pending' => 'Pending',
            'dibalas' => 'Responded',
            'selesai' => 'Completed'
        ];

        return view('admin.ratings.edit', compact('rating', 'units', 'statusOptions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'nullable|string',
            'status' => 'required|in:pending,dibalas,selesai',
            'unit_id' => 'required|exists:units,id'
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update([
            'komentar' => $request->komentar,
            'status' => $request->status,
            'unit_id' => $request->unit_id,
            'dibalas_pada' => $request->status === 'dibalas' ? now() : null
        ]);

        return redirect()->route('admin.ratings.show', $rating->id)
            ->with('success', 'Rating updated successfully');
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('admin.ratings.index')
            ->with('success', 'Rating deleted successfully');
    }

    public function respond(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string|min:10',
            'action_taken' => 'nullable|string'
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update([
            'status' => 'dibalas',
            'dibalas_pada' => now(),
            'metadata' => array_merge($rating->metadata ?? [], [
                'response' => $request->response,
                'action_taken' => $request->action_taken,
                'responded_at' => now()->toDateTimeString()
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Response recorded successfully',
            'rating' => $rating
        ]);
    }

    public function markAsCompleted($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->update(['status' => 'selesai']);

        return response()->json([
            'success' => true,
            'message' => 'Rating marked as completed'
        ]);
    }

    public function getStats()
    {
        $stats = $this->getStatsData();
        $monthlyStats = $this->getMonthlyStatsData();

        return response()->json([
            'success' => true,
            'data' => [
                'overall' => $stats,
                'monthly' => $monthlyStats
            ]
        ]);
    }

    public function export(Request $request)
    {
        $query = Rating::with('unit');

        if ($request->filled('unit')) {
            $query->where('unit_id', $request->unit);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $ratings = $query->get();

        return response()->json([
            'success' => true,
            'data' => $ratings,
            'count' => $ratings->count()
        ]);
    }

    public function analytics()
    {
        $stats = $this->getStatsData();
        $unitRanking = $this->getUnitRankingData(10);
        $monthlyStats = $this->getMonthlyStatsData();

        return view('admin.ratings.analytics', compact(
            'stats',
            'unitRanking',
            'monthlyStats'
        ));
    }

    private function getStatsData()
    {
        $total = Rating::count();
        $pending = Rating::where('status', 'pending')->count();
        $dibalas = Rating::where('status', 'dibalas')->count();
        $selesai = Rating::where('status', 'selesai')->count();

        $averageRating = Rating::get()->filter(function ($rating) {
            return !empty($rating->metadata);
        })->avg(function ($rating) {
            return $rating->getAverageRating();
        });

        return [
            'total' => $total,
            'pending' => $pending,
            'responded' => $dibalas,
            'dibalas' => $dibalas,
            'selesai' => $selesai,
            'average_rating' => round($averageRating ?? 0, 1),
            'response_rate' => $total > 0 ? round((($dibalas + $selesai) / $total) * 100, 1) : 0
        ];
    }

    private function getUnitRankingData($limit = 10)
    {
        return Unit::withCount(['ratings'])
            ->with(['ratings'])
            ->has('ratings')
            ->get()
            ->map(function ($unit) {
                $ratings = $unit->ratings;
                $average = $ratings->avg(function ($rating) {
                    return $rating->getAverageRating();
                });

                $unit->ratings_count = $ratings->count();
                $unit->average_rating = round($average ?? 0, 1);
                $unit->pending_count = $ratings->where('status', 'pending')->count();
                $unit->dibalas_count = $ratings->where('status', 'dibalas')->count();
                $unit->selesai_count = $ratings->where('status', 'selesai')->count();

                return $unit;
            })
            ->sortByDesc('average_rating')
            ->take($limit)
            ->values();
    }

    private function getMonthlyStatsData()
    {
        $months = [];
        $startDate = now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $date = $startDate->copy()->addMonths($i);
            $monthKey = $date->format('Y-m');
            $monthName = $date->format('M Y');

            $monthRatings = Rating::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->get();

            $average = $monthRatings->avg(function ($rating) {
                return $rating->getAverageRating();
            });

            $months[$monthKey] = [
                'month' => $monthName,
                'total' => $monthRatings->count(),
                'pending' => $monthRatings->where('status', 'pending')->count(),
                'dibalas' => $monthRatings->where('status', 'dibalas')->count(),
                'selesai' => $monthRatings->where('status', 'selesai')->count(),
                'average_rating' => round($average ?? 0, 1)
            ];
        }

        return $months;
    }
}