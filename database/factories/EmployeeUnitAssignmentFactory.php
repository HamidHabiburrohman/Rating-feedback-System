<?php

namespace Database\Factories;

use App\Models\Authentication\Employee;
use App\Models\Unit\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeUnitAssignmentFactory extends Factory
{
    protected $model = \App\Models\Employee\EmployeeUnitAssignment::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'unit_id' => Unit::factory(),
            'assigned_by_admin_id' => Admin::factory(),
            'role_in_unit' => $this->faker->optional(0.7)->randomElement(['Kepala', 'Koordinator', 'Staff', 'Teknisi']),
            'assigned_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'ended_at' => $this->faker->optional(0.2)->dateTimeBetween('now', '+6 months'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $employee->id,
        ]);
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function ended(): static
    {
        return $this->state(fn(array $attributes) => [
            'ended_at' => $this->faker->dateTimeBetween('-3 months', '-1 day'),
            'is_active' => false,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'ended_at' => null,
            'is_active' => true,
        ]);
    }
}