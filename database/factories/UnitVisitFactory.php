<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\StudentSession;
use App\Models\UnitVisit;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitVisitFactory extends Factory
{
    protected $model = UnitVisit::class;

    public function definition(): array
    {
        $tanggal = fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d');
        $waktuMasuk = fake()->dateTimeBetween($tanggal . ' 07:00:00', $tanggal . ' 21:00:00');
        $durasiMenit = fake()->numberBetween(5, 180);
        $waktuKeluar = (clone $waktuMasuk)->modify("+{$durasiMenit} minutes");
        
        $shouldHaveExit = fake()->boolean(90);
        
        $session = StudentSession::inRandomOrder()->first();
        
        return [
            'unit_id' => Unit::inRandomOrder()->first()?->id ?? Unit::factory(),
            'session_id' => $session ? $session->session_token : StudentSession::factory()->create()->session_token,
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
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'waktu_keluar' => null,
            'durasi_detik' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $waktuMasuk = $attributes['waktu_masuk'] ?? fake()->dateTimeBetween('-2 days', '-1 hour');
            $durasiMenit = fake()->numberBetween(5, 180);
            $waktuKeluar = (clone $waktuMasuk)->modify("+{$durasiMenit} minutes");
            
            return [
                'waktu_keluar' => $waktuKeluar,
                'durasi_detik' => $durasiMenit * 60,
            ];
        });
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal' => $date,
        ]);
    }
}