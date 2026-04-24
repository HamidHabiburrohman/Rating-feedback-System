<?php

namespace Database\Factories;

use App\Models\AdminReply;
use App\Models\Rating;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminReplyFactory extends Factory
{
    protected $model = AdminReply::class;

    public function definition(): array
    {
        $rating = Rating::inRandomOrder()->first() ?? Rating::factory();
        $repliedAt = fake()->dateTimeBetween($rating->created_at ?? '-30 days', 'now');
        
        return [
            'rating_id' => $rating->id,
            'admin_id' => Admin::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
            'reply_message' => fake()->paragraphs(2, true),
            'replied_at' => $repliedAt,
            'created_at' => $repliedAt,
            'updated_at' => $repliedAt,
        ];
    }

    public function byAdmin(Admin $admin): static
    {
        return $this->state(fn (array $attributes) => [
            'admin_id' => $admin->id,
        ]);
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating_id' => $rating->id,
        ]);
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'replied_at' => now()->subDays(rand(1, 7)),
            'created_at' => now()->subDays(rand(1, 7)),
            'updated_at' => now()->subDays(rand(1, 7)),
        ]);
    }

    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'replied_at' => now()->subMonths(rand(2, 6)),
            'created_at' => now()->subMonths(rand(2, 6)),
            'updated_at' => now()->subMonths(rand(2, 6)),
        ]);
    }
}