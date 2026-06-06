<?php

namespace Database\Factories;

use App\Models\Reports\ReportStatusHistory;
use App\Models\Reports\Report;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportStatusHistoryFactory extends Factory
{
    protected $model = ReportStatusHistory::class;

    public function definition(): array
    {
        $oldStatus = $this->faker->randomElement(['new', 'assigned', 'in_progress', 'replied']);
        $newStatus = match ($oldStatus) {
            'new' => $this->faker->randomElement(['assigned', 'in_progress']),
            'assigned' => $this->faker->randomElement(['in_progress', 'replied']),
            'in_progress' => $this->faker->randomElement(['replied', 'resolved']),
            'replied' => $this->faker->randomElement(['resolved', 'rejected']),
            default => 'resolved',
        };

        $isAdmin = $this->faker->boolean(80);

        return [
            'report_id' => Report::factory(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by_admin_id' => $isAdmin ? Admin::factory() : null,
            'changed_by_employee_id' => !$isAdmin ? Employee::factory() : null,
            'reason' => $this->faker->optional(0.5)->sentence(),
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

    public function withTransition(string $old, string $new): static
    {
        return $this->state(fn(array $attributes) => [
            'old_status' => $old,
            'new_status' => $new,
        ]);
    }
}