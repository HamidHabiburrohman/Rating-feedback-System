<?php

namespace Database\Factories;

use App\Models\Feedback\RatingScore;
use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingScoreFactory extends Factory
{
    protected $model = RatingScore::class;

    public function definition(): array
    {
        return [
            'rating_id' => Rating::factory(),
            'rating_category_id' => RatingCategory::factory(),
            'score' => $this->faker->randomFloat(1, 1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
        ]);
    }

    public function forCategory(RatingCategory $category): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_category_id' => $category->id,
        ]);
    }
}