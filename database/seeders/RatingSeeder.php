<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        
        if ($units->isEmpty()) {
            $this->command->warn('Data unit tidak lengkap untuk RatingSeeder!');
            return;
        }

        $ratings = [];
        $now = now();
        
        // Komentar samples
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
        
        // Generate 100 ratings
        for ($i = 1; $i <= 100; $i++) {
            $unit = $units->random();
            
            // Random rating values (1-5)
            $ratingValues = [
                'kebersihan' => rand(1, 5),
                'pelayanan' => rand(1, 5),
                'kecepatan' => rand(1, 5),
                'keramahan' => rand(1, 5),
                'fasilitas' => rand(1, 5)
            ];
            
            $average = array_sum($ratingValues) / count($ratingValues);
            
            // Determine comment based on average rating
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
            
            // Set response date if status is dibalas/selesai
            $dibalasPada = null;
            if (in_array($status, ['dibalas', 'selesai'])) {
                $dibalasPada = $now->copy()->subDays(rand(1, 30))->subHours(rand(1, 12));
            }
            
            $createdAt = $now->copy()->subDays(rand(0, 90))->subHours(rand(0, 23));
            
            $ratings[] = [
                'unit_id' => $unit->id,
                'session_id' => 'session_' . $i . '_' . uniqid(),
                'visitor_ip' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'komentar' => $komentar,
                'status' => $status,
                // PERBAIKAN: Convert array ke JSON string
                'metadata' => json_encode($ratingValues),
                'dibalas_pada' => $dibalasPada,
                'created_at' => $createdAt,
                'updated_at' => $dibalasPada ?? $createdAt
            ];
        }
        
        // Insert in batches
        foreach (array_chunk($ratings, 25) as $chunk) {
            Rating::insert($chunk);
        }
        
        $this->command->info('100 ratings berhasil di-seed!');
        $this->command->info('Statistik:');
        $this->command->info('   - Pending: ' . count(array_filter($ratings, fn($r) => $r['status'] === 'pending')));
        $this->command->info('   - Dibalas: ' . count(array_filter($ratings, fn($r) => $r['status'] === 'dibalas')));
        $this->command->info('   - Selesai: ' . count(array_filter($ratings, fn($r) => $r['status'] === 'selesai')));
    }
}