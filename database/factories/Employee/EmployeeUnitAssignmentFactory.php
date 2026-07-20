<?php

namespace Database\Factories\Employee;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeUnitAssignmentFactory extends Factory
{
    protected $model = EmployeeUnitAssignment::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'unit_id' => Unit::factory(),
            'assigned_by_admin_id' => Admin::factory(),
            'verified_by_admin_id' => null,
            'status' => $this->faker->randomElement(['assigned', 'accepted', 'in_progress', 'waiting_verification', 'completed', 'cancelled']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'notes' => $this->faker->optional(0.3)->sentence(),
            'assigned_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'started_at' => $this->faker->optional(0.7)->dateTimeBetween('-5 months', 'now'),
            'completed_at' => null,
            'verified_at' => null,
        ];
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_id' => $employee->id,
        ]);
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => $this->faker->dateTimeBetween('-3 months', '-1 day'),
            'verified_at' => $this->faker->optional(0.5)->dateTimeBetween('-2 months', 'now'),
            'verified_by_admin_id' => Admin::factory(),
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $this->faker->randomElement(['assigned', 'accepted', 'in_progress', 'waiting_verification']),
            'completed_at' => null,
            'verified_at' => null,
            'verified_by_admin_id' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'completed_at' => null,
            'verified_at' => null,
            'verified_by_admin_id' => null,
            'notes' => 'Assignment cancelled due to operational changes.',
        ]);
    }
}