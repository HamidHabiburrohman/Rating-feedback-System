<?php

namespace Database\Factories\QRCode;

use App\Models\Authentication\Admin;
use App\Models\Unit\QrCode;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QrCodeFactory extends Factory
{
    protected $model = QrCode::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'code' => strtoupper(Str::uuid()->toString()),
            'qr_image_path' => 'qr-codes/unit-' . $this->faker->unique()->numberBetween(1, 9999) . '.png',
            'is_active' => true,
            'expires_at' => now()->addYear(),
            'generated_by_admin_id' => Admin::factory(),
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);
    }
}