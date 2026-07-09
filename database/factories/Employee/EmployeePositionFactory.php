<?php

namespace Database\Factories\Employee;

use App\Models\Employee\EmployeePosition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeePositionFactory extends Factory
{
    protected $model = EmployeePosition::class;

    public function definition(): array
    {
        $positions = [
            'Kepala Lab', 'Asisten Lab', 'Teknisi', 'Administrasi',
            'Manajer', 'Supervisor', 'Staff', 'Koordinator'
        ];

        $name = $this->faker->randomElement($positions);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->optional(0.6)->paragraph(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}