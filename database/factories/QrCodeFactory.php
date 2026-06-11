<?php

namespace Database\Factories;

use App\Models\Unit\QrCode;
use App\Models\Unit\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QrCodeFactory extends Factory
{
    protected $model = QrCode::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'code' => strtoupper(Str::random(12)),
            'path' => 'qr-codes/' . Str::random(20) . '.png',
            'is_active' => true,
            'expires_at' => $this->faker->optional(0.3)->dateTimeBetween('+1 month', '+1 year'),
            'last_generated_at' => now(),
            'generated_by_admin_id' => Admin::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }
}