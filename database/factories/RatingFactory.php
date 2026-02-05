<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Unit;
use App\Models\VisitorSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition()
    {
        return [
            // Tambahkan tracking_code karena di migration bersifat UNIQUE dan NOT NULL
            'tracking_code' => 'RTG-' . strtoupper(Str::random(10)),
            
            // Relasi ke Unit
            'unit_id' => Unit::inRandomOrder()->first()->id ?? Unit::factory(),
            
            // Sesuai migration: gunakan visitor_session_id, bukan session_id/visitor_ip
            'visitor_session_id' => VisitorSession::inRandomOrder()->first()->id ?? VisitorSession::factory(),
            
            'komentar' => $this->faker->optional(0.7)->sentence(),
            'status' => $this->faker->randomElement(['pending', 'dibalas', 'selesai']),
            
            // Metadata disesuaikan dengan kebutuhan (contoh: rating bintang/kategori)
            'metadata' => [
                'kebersihan' => rand(1, 5),
                'pelayanan' => rand(1, 5),
                'fasilitas' => rand(1, 5),
                'device' => $this->faker->randomElement(['mobile', 'desktop', 'tablet'])
            ],
            
            'dibalas_pada' => null,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function pending()
    {
        return $this->state([
            'status' => 'pending',
            'dibalas_pada' => null,
        ]);
    }

    public function dibalas()
    {
        return $this->state([
            'status' => 'dibalas',
            'dibalas_pada' => now(),
        ]);
    }

    public function selesai()
    {
        return $this->state([
            'status' => 'selesai',
            'dibalas_pada' => now(),
        ]);
    }
}