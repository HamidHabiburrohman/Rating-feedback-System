<?php

namespace Database\Factories;

use App\Models\Report\ReportReply;
use App\Models\Report\Report;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportReplyFactory extends Factory
{
    protected $model = ReportReply::class;

    public function definition(): array
    {
        $isEmployeeReply = $this->faker->boolean(70);

        return [
            'report_id' => Report::factory(),
            'employee_id' => $isEmployeeReply ? Employee::factory() : null,
            'admin_id' => !$isEmployeeReply ? Admin::factory() : null,
            'reply' => $this->faker->paragraph(),
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forReport(Report $report): static
    {
        return $this->state(fn(array $attributes) => [
            'report_id' => $report->id,
        ]);
    }

    public function byEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $employee->id,
            'admin_id' => null,
        ]);
    }

    public function byAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_id' => $admin->id,
            'employee_id' => null,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }
}