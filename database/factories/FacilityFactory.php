<?php

namespace Database\Factories;

use App\Models\Unit\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        $facilities = [
            'AC', 'Proyektor', 'WiFi', 'Whiteboard', 'Toilet', 'Kantin',
            'Parkir', 'Musala', 'Loker', 'Speaker', 'Kursi Roda', 'Ruang Tunggu',
            'Komputer', 'Printer', 'Air Minum', 'Papan Tulis', 'Sound System'
        ];

        $name = $this->faker->randomElement($facilities);
        $iconMap = [
            'AC' => 'air-conditioner', 'Proyektor' => 'projector', 'WiFi' => 'wifi',
            'Whiteboard' => 'whiteboard', 'Toilet' => 'toilet', 'Kantin' => 'cafe',
            'Parkir' => 'parking', 'Musala' => 'mosque', 'Loker' => 'locker',
            'Speaker' => 'speaker', 'Kursi Roda' => 'wheelchair', 'Ruang Tunggu' => 'waiting-room',
            'Komputer' => 'computer', 'Printer' => 'printer', 'Air Minum' => 'water-dispenser',
            'Papan Tulis' => 'whiteboard', 'Sound System' => 'speaker'
        ];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon_key' => $iconMap[$name] ?? 'building',
            'description' => $this->faker->optional(0.5)->sentence(),
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