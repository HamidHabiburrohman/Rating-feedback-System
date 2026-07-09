<?php

namespace Database\Factories\Rating;

use App\Models\Feedback\Rating;
use App\Models\Unit\Unit;
use App\Models\Authentication\Student;
use App\Models\Feedback\UnitVisit;
use App\Models\Unit\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'tracking_code' => 'RTG-' . strtoupper(uniqid()),
            'unit_id' => Unit::factory(),
            'student_id' => Student::factory(),
            'visit_id' => null,
            'qr_code_id' => null,
            'overall_score' => $this->faker->randomFloat(2, 1, 5),
            'comment' => $this->faker->paragraph(),
            'is_comment_censored' => false,
            'status' => $this->faker->randomElement(['active', 'edited', 'archived']),
            'last_edited_at' => null,
            'last_replied_at' => null,
            'metadata' => json_encode([
                'device' => $this->faker->randomElement(['mobile', 'desktop', 'tablet']),
                'browser' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari']),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
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

    public function withVisit(UnitVisit $visit): static
    {
        return $this->state(fn(array $attributes) => [
            'visit_id' => $visit->id,
            'unit_id' => $visit->unit_id,
            'qr_code_id' => $visit->qr_code_id,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'archived',
        ]);
    }

    public function edited(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'edited',
            'last_edited_at' => now(),
        ]);
    }
}