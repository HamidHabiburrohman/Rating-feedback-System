<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Unit;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'tracking_code' => 'RTG-' . strtoupper(uniqid()),
            'unit_id' => Unit::factory(),
            'student_identifier' => Student::inRandomOrder()->first()->student_identifier ?? '20207250',
            'overall_score' => $this->faker->randomFloat(2, 1, 5),
            'comment' => $this->faker->paragraph(),
            'is_comment_censored' => false,
            'status' => $this->faker->randomElement(['active', 'edited', 'archived']),
            'last_edited_at' => null,
            'last_replied_at' => null,
            'metadata' => json_encode([
                'perangkat' => $this->faker->randomElement(['mobile', 'desktop', 'tablet']),
                'browser' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari']),
                'ip_address' => $this->faker->ipv4()
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function byStudent(Student $student): static
    {
        return $this->state(fn (array $attributes) => [
            'student_identifier' => $student->student_identifier,
        ]);
    }
}