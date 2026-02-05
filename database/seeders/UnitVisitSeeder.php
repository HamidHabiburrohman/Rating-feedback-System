<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitVisit;
use App\Models\VisitorSession;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UnitVisitSeeder extends Seeder
{
    public function run()
    {
        $visits = [];
        $sessions = VisitorSession::pluck('session_id')->toArray();
        $units = Unit::pluck('id')->toArray();
        
        if (empty($sessions) || empty($units)) {
            $this->command->warn('Seeder UnitVisit: No sessions or units found. Run other seeders first.');
            return;
        }
        
        foreach ($sessions as $sessionId) {
            $numVisits = rand(1, 5); // 1-5 kunjungan per session
            
            for ($i = 0; $i < $numVisits; $i++) {
                $unitId = $units[array_rand($units)];
                $visitDate = Carbon::now()->subDays(rand(0, 180));
                $waktuMasuk = $visitDate->copy()->addHours(rand(8, 18))->addMinutes(rand(0, 59));
                $waktuKeluar = $waktuMasuk->copy()->addMinutes(rand(5, 120));
                
                $visits[] = [
                    'unit_id' => $unitId,
                    'session_id' => $sessionId,
                    'tanggal' => $visitDate->toDateString(),
                    'waktu_masuk' => $waktuMasuk,
                    'waktu_keluar' => $waktuKeluar,
                    'durasi_detik' => $waktuKeluar->diffInSeconds($waktuMasuk),
                    'metadata' => json_encode(['type' => 'visit']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                if (count($visits) >= 200) {
                    UnitVisit::insert($visits);
                    $visits = [];
                }
            }
        }
        
        if (!empty($visits)) {
            UnitVisit::insert($visits);
        }
        
        $this->command->info('Seeder UnitVisit: ' . UnitVisit::count() . ' data created.');
    }
}