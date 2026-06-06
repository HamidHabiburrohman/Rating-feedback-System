<?php

namespace Database\Factories;

use App\Models\Feedback\RatingReply;
use App\Models\Feedback\Rating;
use App\Models\Authentication\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingReplyFactory extends Factory
{
    protected $model = RatingReply::class;

    public function definition(): array
    {
        return [
            'rating_id' => Rating::factory(),
            'employee_id' => Employee::factory(),
            'reply' => $this->faker->paragraph(),
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
        ]);
    }

    public function byEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $employee->id,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }
}