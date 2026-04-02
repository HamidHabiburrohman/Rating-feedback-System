<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Rating;
use App\Models\Report;
use App\Models\Unit;
use App\Models\ModerationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationLogFactory extends Factory
{
    protected $model = ModerationLog::class;

    public function definition(): array
    {
        $targetTypes = ['Rating', 'Report', 'Unit'];
        $targetType = fake()->randomElement($targetTypes);
        
        $targetId = match ($targetType) {
            'Rating' => Rating::inRandomOrder()->first()?->id ?? 1,
            'Report' => Report::inRandomOrder()->first()?->id ?? 1,
            'Unit' => Unit::inRandomOrder()->first()?->id ?? 1,
        };
        
        $actions = [
            'Rating' => ['censor_comment', 'uncensor_comment', 'edit_rating', 'delete_rating'],
            'Report' => ['assign_report', 'respond_report', 'resolve_report', 'reject_report'],
            'Unit' => ['edit_unit', 'toggle_status', 'delete_unit'],
        ];
        
        $action = fake()->randomElement($actions[$targetType]);
        
        $createdAt = fake()->dateTimeBetween('-2 months', 'now');
        
        return [
            'admin_id' => User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'reason' => fake()->optional(0.7)->sentence(),
            'metadata' => json_encode([
                'ip_address' => fake()->optional()->ipv4(),
                'user_agent' => fake()->optional()->userAgent(),
                'details' => fake()->optional()->words(5),
            ]),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    public function forRating(): static
    {
        $rating = Rating::inRandomOrder()->first() ?? Rating::factory();
        
        return $this->state(fn (array $attributes) => [
            'target_type' => 'Rating',
            'target_id' => $rating instanceof Rating ? $rating->id : $rating,
            'action' => fake()->randomElement(['censor_comment', 'uncensor_comment']),
        ]);
    }

    public function forReport(): static
    {
        $report = Report::inRandomOrder()->first() ?? Report::factory();
        
        return $this->state(fn (array $attributes) => [
            'target_type' => 'Report',
            'target_id' => $report instanceof Report ? $report->id : $report,
            'action' => fake()->randomElement(['assign_report', 'respond_report', 'resolve_report', 'reject_report']),
        ]);
    }

    public function byAdmin(User $admin): static
    {
        return $this->state(fn (array $attributes) => [
            'admin_id' => $admin->id,
        ]);
    }
}