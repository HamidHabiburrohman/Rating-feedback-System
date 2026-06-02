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

            // Profile
            'phone' => $this->faker->optional(0.7, null)->numerify('08##########'), // default null jika tidak terisi
            'location' => $this->faker->optional(0.6, null)->city(),
            'photo' => null,

            // Work details - Perbaikan: jangan gunakan optional() sebelum unique()
            'employee_id' => $this->faker->boolean(80) ? $this->faker->unique()->numerify('EMP-####') : null,
            'position' => $this->faker->boolean(80) ? $this->faker->jobTitle() : null,
            'department' => $this->faker->boolean(70) ? $this->faker->randomElement(['IT', 'HR', 'Marketing', 'Operations', 'Finance']) : null,
            'bio' => $this->faker->boolean(50) ? $this->faker->paragraph() : null,

            // System
            'timezone' => $this->faker->randomElement(['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura']),
            'is_active' => true,
            'two_factor_enabled' => false,

            // Permissions & Preferences
            'permissions' => json_encode($this->faker->randomElements(
                ['manage_units', 'manage_ratings', 'manage_reports', 'manage_facilities', 'manage_settings', 'view_reports'],
                $this->faker->numberBetween(1, 4)
            )),
            'preferences' => json_encode([
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['id', 'en']),
                'notifications' => $this->faker->boolean(),
            ]),

            // Login audit
            'login_count' => $this->faker->numberBetween(0, 100),
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),

            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    // State methods
    public function superAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'super_admin',
            'permissions' => json_encode(['*']),
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function unitAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'unit',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // Method khusus untuk membuat admin dengan employee_id wajib
    public function withEmployeeId(): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $this->faker->unique()->numerify('EMP-####'),
        ]);
    }
}