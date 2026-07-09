<?php

namespace Database\Factories\Student;

use App\Models\Authentication\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, 2025);
        $sequence = $this->faker->unique()->numberBetween(1, 9999);

        return [
            'student_identifier' => $year . str_pad($sequence, 4, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'is_active' => true,
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'major' => $this->faker->optional(0.8)->randomElement([
                'Teknik Informatika', 'Teknik Sipil', 'Teknik Elektro',
                'Teknik Mesin', 'Teknik Industri', 'Sistem Informasi'
            ]),
            'class_year' => (string) $this->faker->optional(0.8)->numberBetween(2020, 2025),
            'bio' => $this->faker->optional(0.5)->paragraph(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'location' => $this->faker->optional(0.6)->city(),
            'portfolio_url' => $this->faker->optional(0.3)->url(),
            'linkedin_url' => $this->faker->optional(0.3)->url(),
            'photo' => null,
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function fromYear(int $year): static
    {
        return $this->state(function (array $attributes) use ($year) {
            $studentId = $year . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
            return [
                'student_identifier' => $studentId,
            ];
        });
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