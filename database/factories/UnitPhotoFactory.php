<?php

namespace Database\Factories;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitPhoto;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitPhotoFactory extends Factory
{
    protected $model = UnitPhoto::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'uploaded_by_admin_id' => Admin::factory(),
            'disk' => 'public',
            'original_path' => 'photos/placeholder.jpg',
            'thumbnail_path' => 'photos/thumb/placeholder.jpg',
            'medium_path' => 'photos/medium/placeholder.jpg',
            'large_path' => 'photos/large/placeholder.jpg',
            'file_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => $this->faker->numberBetween(100000, 2000000),
            'alt_text' => $this->faker->optional(0.5)->sentence(),
            'sort_order' => 0,
            'is_primary' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}