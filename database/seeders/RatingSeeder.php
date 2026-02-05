<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Unit;
use App\Models\VisitorSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $sessions = VisitorSession::all();
        
        if ($units->isEmpty() || $sessions->isEmpty()) {
            return;
        }

        $positiveComments = [
            'Pelayanan sangat ramah dan cepat, staff sangat membantu.',
            'Kebersihan unit terjaga dengan baik, lingkungan nyaman.',
            'Proses pelayanan efisien, tidak perlu menunggu lama.',
            'Fasilitas lengkap dan terawat dengan baik.',
            'Staff profesional dan komunikatif, sangat memuaskan.'
        ];
        
        $neutralComments = [
            'Cukup baik, tapi bisa ditingkatkan lagi.',
            'Pelayanan standar, tidak ada yang spesial.',
            'Lumayan, sesuai dengan ekspektasi.',
            'Biasa saja, tidak buruk tapi tidak istimewa.',
            'Layanan acceptable, ruangan cukup bersih.'
        ];
        
        $negativeComments = [
            'Pelayanan lambat, staff kurang ramah.',
            'Kebersihan kurang terjaga, banyak sampah berserakan.',
            'Fasilitas banyak yang rusak, perlu perbaikan.',
            'Proses berbelit-belit, tidak efisien.',
            'Staff kurang pengetahuan, sering salah informasi.'
        ];
        
        $ratings = [];
        $now = now();
        
        for ($i = 1; $i <= 100; $i++) {
            $unit = $units->random();
            $session = $sessions->random();
            
            $ratingValues = [
                'kebersihan' => rand(1, 5),
                'pelayanan' => rand(1, 5),
                'kecepatan' => rand(1, 5),
                'keramahan' => rand(1, 5),
                'fasilitas' => rand(1, 5)
            ];
            
            $average = array_sum($ratingValues) / count($ratingValues);
            
            if ($average >= 4) {
                $komentar = $positiveComments[array_rand($positiveComments)];
                $status = rand(0, 1) ? 'selesai' : 'dibalas';
            } elseif ($average >= 2.5) {
                $komentar = $neutralComments[array_rand($neutralComments)];
                $status = rand(0, 1) ? 'pending' : 'dibalas';
            } else {
                $komentar = $negativeComments[array_rand($negativeComments)];
                $status = rand(0, 1) ? 'pending' : 'dibalas';
            }
            
            $createdAt = $now->copy()->subDays(rand(0, 90));
            
            $ratings[] = [
                'tracking_code' => 'RTG-' . strtoupper(Str::random(10)) . $i,
                'unit_id' => $unit->id,
                'visitor_session_id' => $session->id,
                'komentar' => $komentar,
                'status' => $status,
                'metadata' => json_encode($ratingValues),
                'dibalas_pada' => ($status === 'dibalas' || $status === 'selesai') ? $createdAt->addHours(2) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ];
        }
        
        foreach (array_chunk($ratings, 25) as $chunk) {
            Rating::insert($chunk);
        }
    }
}