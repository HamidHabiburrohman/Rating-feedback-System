<?php

namespace Database\Seeders;

use App\Models\VisitorSession;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VisitorSessionSeeder extends Seeder
{
    public function run()
    {
        $sessions = [];
        $startDate = Carbon::now()->subMonths(6); // Data 6 bulan terakhir
        
        for ($i = 0; $i < 500; $i++) { // 500 session
            $sessionId = 'visitor_' . uniqid();
            $createdAt = $startDate->copy()->addDays(rand(0, 180))->addHours(rand(0, 23));
            
            $sessions[] = [
                'session_id' => $sessionId,
                'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                'user_agent' => $this->getRandomUserAgent(),
                'metadata' => json_encode(['device' => $this->getRandomDevice()]),
                'terakhir_aktivitas' => $createdAt->copy()->addMinutes(rand(5, 120)),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
            
            if (count($sessions) >= 100) {
                VisitorSession::insert($sessions);
                $sessions = [];
            }
        }
        
        if (!empty($sessions)) {
            VisitorSession::insert($sessions);
        }
        
        $this->command->info('Seeder VisitorSession: ' . VisitorSession::count() . ' data created.');
    }
    
    private function getRandomUserAgent()
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15',
        ];
        return $agents[array_rand($agents)];
    }
    
    private function getRandomDevice()
    {
        $devices = ['desktop', 'mobile', 'tablet'];
        return $devices[array_rand($devices)];
    }
}