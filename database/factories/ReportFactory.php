<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Unit;
use App\Models\VisitorSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'tracking_code' => 'TRK-' . strtoupper(Str::random(10)),
            'unit_id' => Unit::inRandomOrder()->first()->id ?? Unit::factory(),
            'visitor_session_id' => VisitorSession::inRandomOrder()->first()->id ?? VisitorSession::factory(),
            'judul' => $this->faker->sentence(4),
            'deskripsi' => $this->faker->paragraph(),
            'tipe' => $this->faker->randomElement(['masalah', 'saran', 'keluhan', 'lainnya']),
            'prioritas' => $this->faker->randomElement(['rendah', 'sedang', 'tinggi', 'kritis']),
            'status' => $this->faker->randomElement(['baru', 'diproses', 'selesai', 'ditolak']),
            'admin_id' => User::inRandomOrder()->first()->id ?? null,
            'tanggapan_admin' => $this->faker->boolean(50) ? $this->faker->sentence() : null,
            'ditanggapi_pada' => $this->faker->boolean(50) ? now() : null,
        ];
    }
}