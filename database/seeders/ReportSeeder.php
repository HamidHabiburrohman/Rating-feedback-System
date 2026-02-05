<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Unit;
use App\Models\VisitorSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    public function run()
    {
        // Cara 1: Pakai Factory (Lebih Aman)
        Report::factory()->count(20)->create();

        // Cara 2: Manual (Pastikan kolom & enum benar)
        Report::create([
            'tracking_code' => 'TRK-' . strtoupper(Str::random(10)),
            'unit_id' => Unit::first()->id,
            'visitor_session_id' => VisitorSession::first()->id,
            'judul' => 'Kendala AC Ruang IT',
            'deskripsi' => 'AC mengeluarkan bunyi bising dan tidak dingin.',
            'tipe' => 'masalah', // Jangan pakai 'pujian'
            'prioritas' => 'tinggi',
            'status' => 'baru',
            'admin_id' => User::first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}