<?php

namespace Database\Factories;

use App\Models\Unit\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitTypeFactory extends Factory
{
    protected $model = UnitType::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Laboratorium', 'Perpustakaan', 'Ruang Kelas', 'Auditorium',
            'Kantin', 'Olahraga', 'Klinik', 'Layanan', 'Pusat Studi'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon_key' => $this->faker->randomElement([
                'flask', 'book-open', 'presentation', 'theater',
                'coffee', 'dumbbell', 'heart-pulse', 'building'
            ]),
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