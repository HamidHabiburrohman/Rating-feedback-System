<?php

namespace Database\Factories;

use App\Models\Authentication\Admin;
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
            'role' => $this->faker->randomElement(['admin', 'super_admin']),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'photo' => null,
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'location' => $this->faker->optional(0.6)->city(),
            'employee_id' => $this->faker->boolean(80) ? $this->faker->unique()->numerify('EMP-####') : null,
            'position' => $this->faker->optional(0.7)->jobTitle(),
            'department' => $this->faker->optional(0.6)->randomElement(['IT', 'HR', 'Marketing', 'Operations', 'Finance']),
            'bio' => $this->faker->optional(0.5)->paragraph(),
            'timezone' => $this->faker->randomElement(['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura']),
            'is_active' => true,
            'two_factor_enabled' => false,
            'preferences' => json_encode([
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['id', 'en']),
            ]),
            'login_count' => $this->faker->numberBetween(0, 100),
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}