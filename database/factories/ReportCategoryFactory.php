<?php

namespace Database\Factories;

use App\Models\Reports\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportCategoryFactory extends Factory
{
    protected $model = ReportCategory::class;

    public function definition(): array
    {
        $categories = [
            'Komentar Tidak Pantas', 'Informasi Palsu', 'Ujaran Kebencian',
            'Spam', 'Konten Ilegal', 'Pelanggaran Hak Cipta', 'Pencemaran Nama Baik'
        ];

        $name = $this->faker->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'is_active' => true,
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
}