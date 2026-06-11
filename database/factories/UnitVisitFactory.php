<?php

namespace Database\Factories;

use App\Models\Feedback\UnitVisit;
use App\Models\Unit\Unit;
use App\Models\Authentication\Student;
use App\Models\Unit\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitVisitFactory extends Factory
{
    protected $model = UnitVisit::class;

    public function definition(): array
    {
        $visitedAt = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'unit_id' => Unit::factory(),
            'student_id' => Student::factory(),
            'qr_code_id' => QrCode::factory(),
            'visited_at' => $visitedAt,
            'is_gps_validated' => $this->faker->boolean(80),
            'latitude' => $this->faker->optional(0.8)->latitude(),
            'longitude' => $this->faker->optional(0.8)->longitude(),
            'validation_radius_meters' => 100,
            'metadata' => json_encode([
                'source' => $this->faker->randomElement(['qr_scan', 'manual']),
                'device' => $this->faker->randomElement(['mobile', 'tablet']),
            ]),
            'created_at' => $visitedAt,
            'updated_at' => $visitedAt,
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function byStudent(Student $student): static
    {
        return $this->state(fn(array $attributes) => [
            'student_id' => $student->id,
        ]);
    }

    public function withQrCode(QrCode $qrCode): static
    {
        return $this->state(fn(array $attributes) => [
            'qr_code_id' => $qrCode->id,
        ]);
    }

    public function gpsValidated(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_gps_validated' => true,
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ]);
    }

    public function gpsNotValidated(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_gps_validated' => false,
            'latitude' => null,
            'longitude' => null,
        ]);
    }
}