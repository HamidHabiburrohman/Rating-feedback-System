<?php

namespace Database\Factories;

use App\Models\System\Export;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExportFactory extends Factory
{
    protected $model = Export::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']);

        return [
            'admin_id' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'export_type' => $this->faker->randomElement(['ratings', 'reports', 'units', 'users']),
            'format' => $this->faker->randomElement(['csv', 'excel', 'pdf']),
            'file_name' => $this->faker->word() . '.' . $this->faker->randomElement(['csv', 'xlsx', 'pdf']),
            'file_path' => 'exports/' . $this->faker->uuid() . '.' . $this->faker->randomElement(['csv', 'xlsx', 'pdf']),
            'file_size' => $this->faker->numberBetween(10000, 5000000),
            'status' => $status,
            'filters' => json_encode([]),
            'completed_at' => $status === 'completed' ? $this->faker->dateTime() : null,
            'expires_at' => $status === 'completed' ? $this->faker->dateTimeBetween('+1 day', '+1 month') : null,
            'download_count' => $this->faker->numberBetween(0, 10),
            'last_downloaded_at' => $this->faker->optional(0.3)->dateTime(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function forAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_id' => $admin->id,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'processing',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'failed',
        ]);
    }
}