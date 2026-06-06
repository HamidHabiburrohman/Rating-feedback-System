<?php

namespace Database\Factories;

use App\Models\System\ModerationLog;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationLogFactory extends Factory
{
    protected $model = ModerationLog::class;

    public function definition(): array
    {
        $targetTypes = ['Rating', 'Report', 'Unit'];
        $targetType = $this->faker->randomElement($targetTypes);

        $actions = match ($targetType) {
            'Rating' => ['censor_comment', 'uncensor_comment', 'edit_rating', 'delete_rating'],
            'Report' => ['assign', 'respond', 'resolve', 'reject'],
            'Unit' => ['edit_unit', 'toggle_status', 'delete_unit'],
        };

        return [
            'admin_id' => Admin::factory(),
            'action' => $this->faker->randomElement($actions),
            'target_type' => $targetType,
            'target_id' => 1,
            'reason' => $this->faker->optional(0.7)->sentence(),
            'metadata' => json_encode([
                'ip_address' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
            ]),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
    }

    public function forRating(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'Rating',
            'action' => $this->faker->randomElement(['censor_comment', 'uncensor_comment', 'edit_rating', 'delete_rating']),
        ]);
    }

    public function forReport(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'Report',
            'action' => $this->faker->randomElement(['assign', 'respond', 'resolve', 'reject']),
        ]);
    }

    public function forUnit(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'Unit',
            'action' => $this->faker->randomElement(['edit_unit', 'toggle_status', 'delete_unit']),
        ]);
    }

    public function byAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_id' => $admin->id,
        ]);
    }
}