<?php

namespace Database\Factories;

use App\Models\Feedback\RatingCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RatingCategoryFactory extends Factory
{
    protected $model = RatingCategory::class;

    public function definition(): array
    {
        $categories = [
            'Kebersihan' => 1,
            'Kenyamanan' => 2,
            'Pelayanan' => 3,
            'Fasilitas' => 4,
            'Aksesibilitas' => 5,
        ];

        $name = $this->faker->randomElement(array_keys($categories));
        $sortOrder = $categories[$name];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'sort_order' => $sortOrder,
            'min_score' => 1.0,
            'max_score' => 5.0,
            'default_score' => 3.0,
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

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}