<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\Student;
use App\Models\Rating;
use App\Models\RatingCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        $nilaiFasilitas = fake()->randomFloat(1, 2, 5);
        $nilaiPelayanan = fake()->randomFloat(1, 2, 5);
        $nilaiKualitas = fake()->randomFloat(1, 2, 5);
        $nilaiRataRata = round(($nilaiFasilitas + $nilaiPelayanan + $nilaiKualitas) / 3, 2);
        
        $status = fake()->randomElement(['active', 'edited', 'archived']);
        $waktuDibuat = fake()->dateTimeBetween('-6 months', 'now');
        $waktuDiedit = null;
        
        if ($status === 'edited') {
            $waktuDiedit = fake()->dateTimeBetween($waktuDibuat, 'now');
        }
        
        return [
            'tracking_code' => 'RTG-' . strtoupper(uniqid()),
            'unit_id' => Unit::factory(),
            'student_id' => Student::factory(),
            'overall_score' => $nilaiRataRata,
            'comment' => fake()->optional(0.7)->paragraph(),
            'is_comment_censored' => fake()->boolean(10),
            'status' => $status,
            'last_edited_at' => $waktuDiedit,
            'last_replied_at' => null,
            'metadata' => json_encode([
                'perangkat' => fake()->randomElement(['mobile', 'desktop', 'tablet']),
                'browser' => fake()->randomElement(['Chrome', 'Firefox', 'Safari']),
                'ip_address' => fake()->ipv4(),
            ]),
            'created_at' => $waktuDibuat,
            'updated_at' => $waktuDiedit ?? $waktuDibuat,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Rating $rating) {
            // Biarkan RatingScoreSeeder yang handle pembuatan nilai per kategori
        });
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'last_edited_at' => null,
        ]);
    }

    public function censored(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_comment_censored' => true,
        ]);
    }

    public function withReply(): static
    {
        return $this->afterCreating(function (Rating $rating) {
            $waktuDibalas = fake()->dateTimeBetween($rating->created_at, 'now');
            
            DB::table('admin_replies')->insert([
                'rating_id' => $rating->id,
                'admin_id' => \App\Models\User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
                'reply_message' => fake()->paragraph(),
                'replied_at' => $waktuDibalas,
                'created_at' => $waktuDibalas,
                'updated_at' => $waktuDibalas,
            ]);
            
            $rating->update(['last_replied_at' => $waktuDibalas]);
        });
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function byStudent(Student $student): static
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $student->id,
        ]);
    }
}