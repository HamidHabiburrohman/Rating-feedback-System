<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudentSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentSessionFactory extends Factory
{
    protected $model = StudentSession::class;

    public function definition(): array
    {
        $lastActivity = fake()->dateTimeBetween('-7 days', 'now');
        $createdAt = fake()->dateTimeBetween('-8 days', '-1 day');
        
        if ($createdAt > $lastActivity) {
            $createdAt = clone $lastActivity;
            $createdAt->modify('-30 minutes');
        }
        
        // Ambil student yang sudah ada, jangan buat baru
        $student = Student::inRandomOrder()->first();
        
        return [
            'student_id' => $student ? $student->id : Student::factory(),
            'session_token' => Str::random(40),
            'ip_address' => fake()->optional(0.8)->ipv4(),
            'user_agent' => fake()->userAgent(),
            'last_activity_at' => $lastActivity,
            'created_at' => $createdAt,
            'updated_at' => $lastActivity,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_activity_at' => now()->subMinutes(rand(1, 30)),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_activity_at' => now()->subHours(rand(2, 48)),
        ]);
    }

    public function forStudent(Student $student): static
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $student->id,
        ]);
    }
}