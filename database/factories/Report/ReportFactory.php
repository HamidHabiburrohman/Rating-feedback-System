<?php

namespace Database\Factories\Report;

use App\Models\Report\Report;
use App\Models\Feedback\Rating;
use App\Models\Report\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $rating = Rating::inRandomOrder()->first() ?? Rating::factory();
        $category = ReportCategory::inRandomOrder()->first();
        
        if (!$category) {
            $category = ReportCategory::factory()->create();
        }

        $status = $this->faker->randomElement([
            'new', 'assigned', 'in_progress', 
            'replied', 'resolved', 'rejected'
        ]);

        return [
            'tracking_code' => 'RPT-' . strtoupper(uniqid()),
            'rating_id' => $rating->id,
            'unit_id' => $rating->unit_id,
            'student_id' => $rating->student_id,
            'report_category_id' => $category->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(2, true),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => $status,
            'resolved_at' => $status === 'resolved' 
                ? $this->faker->dateTimeBetween('-1 month', 'now') 
                : null,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween(
                $attributes['created_at'], 
                'now'
            )
        ];
    }

    public function forRating(Rating $rating): static
    {
        $category = ReportCategory::inRandomOrder()->first() ?? ReportCategory::factory();
        
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
            'unit_id' => $rating->unit_id,
            'student_id' => $rating->student_id,
            'report_category_id' => $category->id
        ]);
    }

    public function asNew(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'new',
            'resolved_at' => null
        ]);
    }

    public function asResolved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now()
        ]);
    }

    public function asRejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected'
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn(array $attributes) => [
            'priority' => 'critical'
        ]);
    }
}