<?php

namespace Database\Factories;

use App\Models\System\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->word(),
            'type' => 'string',
            'group' => 'general',
            'subgroup' => null,
            'label' => $this->faker->words(3, true),
            'description' => $this->faker->optional(0.5)->sentence(),
            'hint' => $this->faker->optional(0.3)->sentence(),
            'options' => null,
            'validation_rules' => null,
            'sort_order' => 0,
            'is_editable' => true,
            'is_visible' => true,
            'is_public' => false,
            'required_permission' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withGroup(string $group): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => $group,
        ]);
    }

    public function withType(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => $type,
        ]);
    }

    public function notEditable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_editable' => false,
        ]);
    }
}