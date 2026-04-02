<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, 2024);
        $sequence = $this->faker->unique()->numberBetween(1, 9999);
        
        return [
            'student_identifier' => $year . str_pad($sequence, 4, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    public function fromYear(int $year): static
    {
        return $this->state(function (array $attributes) use ($year) {
            $studentId = $year . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
            return [
                'student_identifier' => $studentId,
            ];
        });
    }
}