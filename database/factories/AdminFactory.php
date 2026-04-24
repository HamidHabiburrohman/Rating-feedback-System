<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => $this->faker->randomElement(['admin', 'super_admin', 'unit']),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'position' => $this->faker->optional(0.8)->jobTitle(),
            'bio' => $this->faker->optional(0.5)->paragraph(),
            'location' => $this->faker->optional(0.6)->city(),
            'permissions' => json_encode($this->faker->randomElements(
                ['manage_units', 'manage_ratings', 'manage_reports', 'manage_facilities', 'manage_settings', 'view_reports'],
                $this->faker->numberBetween(1, 4)
            )),
            'preferences' => json_encode([
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['id', 'en']),
                'notifications' => $this->faker->boolean(),
            ]),
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
            'permissions' => json_encode(['*']),
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function unitAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'unit',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}