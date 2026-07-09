<?php

namespace Database\Factories\Unit;

use App\Models\Unit\Unit;
use App\Models\Unit\Facility;
use App\Models\Unit\UnitFacility;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFacilityFactory extends Factory
{
    protected $model = UnitFacility::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'facility_id' => Facility::factory(),
            'value' => $this->faker->optional(0.3)->sentence(),
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

    public function forFacility(Facility $facility): static
    {
        return $this->state(fn(array $attributes) => [
            'facility_id' => $facility->id,
        ]);
    }
}