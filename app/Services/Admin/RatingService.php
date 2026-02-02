<?php

namespace App\Services\Admin;

use App\Models\Rating;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class RatingService
{
    public function getAllRatings($filters = [])
    {
        $query = Rating::with('unit');
        
        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('komentar', 'LIKE', "%{$search}%")
                  ->orWhereHas('unit', function($q) use ($search) {
                      $q->where('nama_unit', 'LIKE', "%{$search}%")
                        ->orWhere('kode_unit', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';
        
        return $query->orderBy($sort, $order);
    }

    public function getRatingStats($unitId = null)
    {
        $query = Rating::query();
        
        if ($unitId) {
            $query->where('unit_id', $unitId);
        }
        
        $total = $query->count();
        $pending = $query->clone()->where('status', 'pending')->count();
        $responded = $query->clone()->where('status', 'dibalas')->count();
        $completed = $query->clone()->where('status', 'selesai')->count();
        
        // Average rating
        $avgRating = $this->getAverageRating($unitId);
        
        return [
            'total' => $total,
            'pending' => $pending,
            'responded' => $responded,
            'completed' => $completed,
            'average_rating' => $avgRating
        ];
    }

    public function getAverageRating($unitId = null)
    {
        $query = Rating::query();
        
        if ($unitId) {
            $query->where('unit_id', $unitId);
        }
        
        $ratings = $query->get();
        $total = 0;
        $count = 0;
        
        foreach ($ratings as $rating) {
            $avg = $rating->getAverageRating();
            if ($avg > 0) {
                $total += $avg;
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 1) : 0;
    }

    public function getUnitRanking($limit = 10)
    {
        return Unit::withCount(['ratings' => function($query) {
                $query->where('status', 'selesai');
            }])
            ->with(['ratings'])
            ->having('ratings_count', '>', 0)
            ->orderByDesc('ratings_count')
            ->limit($limit)
            ->get()
            ->map(function($unit) {
                $unit->average_rating = $this->getAverageRating($unit->id);
                return $unit;
            });
    }

    public function createRating(array $data)
    {
        return DB::transaction(function() use ($data) {
            $rating = Rating::create($data);
            
            // Auto-create message for low ratings
            $avgRating = $rating->getAverageRating();
            if ($avgRating < 3) {
                $this->createLowRatingNotification($rating);
            }
            
            return $rating;
        });
    }

    public function updateRating($id, array $data)
    {
        $rating = Rating::findOrFail($id);
        $rating->update($data);
        
        return $rating;
    }

    public function deleteRating($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();
        
        return true;
    }

    public function respondToRating($id, array $responseData)
    {
        $rating = Rating::findOrFail($id);
        
        $updateData = [
            'status' => 'dibalas',
            'dibalas_pada' => now(),
            'metadata' => array_merge(
                (array) $rating->metadata,
                ['admin_response' => $responseData]
            )
        ];
        
        $rating->update($updateData);
        
        return $rating;
    }

    private function createLowRatingNotification(Rating $rating)
    {
        // Create automatic message for admin
        \App\Models\Message::create([
            'pengirim_tipe' => 'system',
            'pengirim_id' => 0,
            'penerima_tipe' => 'admin',
            'penerima_id' => 1, // Default admin
            'unit_id' => $rating->unit_id,
            'judul' => 'Low Rating Alert: ' . $rating->unit->nama_unit,
            'pesan' => 'Rating rendah diterima: ' . $rating->getAverageRating() . '/5. Komentar: ' . ($rating->komentar ?? 'Tidak ada komentar'),
            'kategori' => 'rating_feedback',
            'prioritas' => 'penting',
            'status' => 'terkirim',
            'perlu_tindakan' => true,
            'data_tindakan' => [
                'rating_id' => $rating->id,
                'unit_id' => $rating->unit_id,
                'rating_value' => $rating->getAverageRating(),
                'action_required' => 'review_and_improve'
            ]
        ]);
    }

    public function getMonthlyStats($year = null)
    {
        $year = $year ?? date('Y');
        
        return Rating::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "dibalas" THEN 1 ELSE 0 END) as responded'),
                DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as completed')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->month => $item];
            });
    }
}