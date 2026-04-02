<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\StudentSession;
use App\Models\UnitVisit;
use Illuminate\Database\Seeder;

class UnitVisitSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $sessions = StudentSession::all();

        if ($units->isEmpty() || $sessions->isEmpty()) {
            return;
        }

        foreach ($units as $unit) {
            $visitCount = rand(5, 20);
            
            for ($i = 0; $i < $visitCount; $i++) {
                $tanggal = fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d');
                $waktuMasuk = fake()->dateTimeBetween($tanggal . ' 07:00:00', $tanggal . ' 21:00:00');
                $durasiMenit = fake()->numberBetween(5, 180);
                $waktuKeluar = (clone $waktuMasuk)->modify("+{$durasiMenit} minutes");
                
                $shouldHaveExit = fake()->boolean(90);
                
                $session = $sessions->random();
                
                UnitVisit::create([
                    'unit_id' => $unit->id,
                    'session_id' => $session->session_token,
                    'tanggal' => $tanggal,
                    'waktu_masuk' => $waktuMasuk,
                    'waktu_keluar' => $shouldHaveExit ? $waktuKeluar : null,
                    'durasi_detik' => $shouldHaveExit ? $durasiMenit * 60 : null,
                    'metadata' => json_encode([
                        'source' => fake()->randomElement(['qr_scan', 'manual', 'nfc']),
                        'device' => fake()->randomElement(['mobile', 'tablet', 'desktop']),
                    ]),
                    'created_at' => $waktuMasuk,
                    'updated_at' => $waktuKeluar ?? $waktuMasuk,
                ]);
            }
        }
    }
}