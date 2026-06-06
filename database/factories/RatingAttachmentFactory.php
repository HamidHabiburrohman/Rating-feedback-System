<?php

namespace Database\Factories;

use App\Models\Feedback\RatingAttachment;
use App\Models\Feedback\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingAttachmentFactory extends Factory
{
    protected $model = RatingAttachment::class;

    public function definition(): array
    {
        return [
            'rating_id' => Rating::factory(),
            'path' => 'rating-attachments/' . $this->faker->uuid() . '.jpg',
            'original_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(100000, 5000000),
            'disk' => 'public',
            'sort_order' => 0,
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

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}