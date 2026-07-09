<?php

namespace Database\Factories\Notification;

use App\Models\System\Notification;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $notifiableType = $this->faker->randomElement([
            Admin::class,
            Employee::class,
            Student::class
        ]);

        $notifiable = null;

        if ($notifiableType === Admin::class) {
            $notifiable = Admin::inRandomOrder()->first() ?? Admin::factory()->create();
        } elseif ($notifiableType === Employee::class) {
            $notifiable = Employee::inRandomOrder()->first() ?? Employee::factory()->create();
        } else {
            $notifiable = Student::inRandomOrder()->first() ?? Student::factory()->create();
        }

        return [
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiable->id,
            'type' => $this->faker->randomElement(['rating_reply', 'report_reply', 'report_status', 'system']),
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'data' => json_encode([]),
            'read_at' => $this->faker->optional(0.5)->dateTime(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn(array $attributes) => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn(array $attributes) => [
            'read_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function forAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'notifiable_type' => Admin::class,
            'notifiable_id' => $admin->id,
        ]);
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'notifiable_type' => Employee::class,
            'notifiable_id' => $employee->id,
        ]);
    }

    public function forStudent(Student $student): static
    {
        return $this->state(fn(array $attributes) => [
            'notifiable_type' => Student::class,
            'notifiable_id' => $student->id,
        ]);
    }
}