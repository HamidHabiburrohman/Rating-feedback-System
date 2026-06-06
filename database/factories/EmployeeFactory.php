<?php

namespace Database\Factories;

use App\Models\Authentication\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'employee_id' => $this->faker->unique()->numerify('EMP-####'),
            'photo' => null,
            'phone' => $this->faker->optional(0.8)->phoneNumber(),
            'position' => $this->faker->optional(0.7)->jobTitle(),
            'department' => $this->faker->optional(0.6)->randomElement([
                'Laboratorium', 'Perpustakaan', 'Kesehatan', 'Kemahasiswaan',
                'Fasilitas Umum', 'Teknologi Informasi', 'Akademik', 'Keuangan'
            ]),
            'is_active' => true,
            'timezone' => 'Asia/Jakarta',
            'preferences' => json_encode([
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['id', 'en']),
            ]),
            'login_count' => $this->faker->numberBetween(0, 50),
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
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

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withDepartment(string $department): static
    {
        return $this->state(fn(array $attributes) => [
            'department' => $department,
        ]);
    }
}