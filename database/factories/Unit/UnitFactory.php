<?php

namespace Database\Factories\Unit;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitType;
use App\Models\Unit\UnitDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $unitType = UnitType::inRandomOrder()->first() ?? UnitType::factory();
        $department = UnitDepartment::inRandomOrder()->first() ?? UnitDepartment::factory();

        $prefixes = ['Utama', 'Sentra', 'Pusat', 'Fasilitas', 'Gedung', 'Area', 'Ruang'];

        $buildings = [
            'Gedung Rektorat', 'Gedung Fakultas Teknik', 'Gedung Fakultas Ekonomi',
            'Gedung Fakultas Hukum', 'Gedung Fakultas Kedokteran', 'Gedung FIK',
            'Gedung Serbaguna', 'Student Center', 'Gedung Perpustakaan',
            'Gedung Laboratorium', 'Gedung Kuliah Bersama', 'Gedung Olahraga'
        ];

        $floors = ['Lantai 1', 'Lantai 2', 'Lantai 3', 'Lantai 4', 'Lantai Dasar'];

        $name = $unitType->name . ' ' . $this->faker->randomElement($prefixes) . ' ' . $this->faker->numberBetween(1, 99);

        return [
            'code' => strtoupper($this->faker->unique()->bothify('??-###')),
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(4)),
            'unit_type_id' => $unitType->id,
            'unit_department_id' => $department->id,
            'description' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->randomElement($buildings) . ' ' . $this->faker->randomElement($floors),
            'building' => $this->faker->randomElement($buildings),
            'floor' => $this->faker->randomElement($floors),
            'phone' => $this->faker->optional(0.6)->phoneNumber(),
            'email' => $this->faker->optional(0.5)->email(),
            'open_time' => $this->faker->randomElement(['07:00:00', '08:00:00', '09:00:00']),
            'close_time' => $this->faker->randomElement(['16:00:00', '17:00:00', '18:00:00', '20:00:00', '22:00:00']),
            'capacity' => $this->faker->numberBetween(20, 1000),
            'is_active' => $this->faker->boolean(90),
            'operational_status' => $this->faker->randomElement(['open', 'full', 'maintenance', 'closed']),
            'primary_qr_code_id' => null,
            'metadata' => json_encode([
                'has_ac' => $this->faker->boolean(80),
                'has_wifi' => $this->faker->boolean(70),
                'has_projector' => $this->faker->boolean(50),
                'has_parking' => $this->faker->boolean(85),
            ]),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withPrimaryQrCode(int $qrCodeId): static
    {
        return $this->state(fn(array $attributes) => [
            'primary_qr_code_id' => $qrCodeId,
        ]);
    }
}