<?php

namespace Database\Factories;

use App\Models\Units\UnitDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitDepartmentFactory extends Factory
{
    protected $model = UnitDepartment::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Hukum',
            'Fakultas Kedokteran', 'Direktorat Akademik', 'Kemahasiswaan',
            'UPT Perpustakaan', 'UPT Laboratorium', 'Fakultas Ilmu Komputer',
            'Fakultas Psikologi', 'Fakultas Pertanian', 'Fakultas Peternakan'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => $this->faker->optional(0.7)->unique()->bothify('??###'),
            'description' => $this->faker->optional(0.6)->paragraph(),
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