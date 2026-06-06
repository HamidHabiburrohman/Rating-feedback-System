<?php

namespace Database\Factories;

use App\Models\Reports\ReportAttachment;
use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportAttachmentFactory extends Factory
{
    protected $model = ReportAttachment::class;

    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'path' => 'report-attachments/' . $this->faker->uuid() . '.jpg',
            'original_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(100000, 5000000),
            'disk' => 'public',
            'sort_order' => 0,
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

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}