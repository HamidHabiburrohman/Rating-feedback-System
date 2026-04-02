<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Unit;
use App\Models\Rating;
use App\Models\RatingScore;
use App\Models\Report;
use App\Models\RatingCategory;
use Illuminate\Support\Facades\Hash;

class StudentWithActivitySeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // BAGIAN 1: CREATE STUDENTS (SAMA KAYA StudentSeeder)
        // ============================================
        $students = [
            [
                'student_identifier' => '20207250',
                'name' => 'Nilam Susanti',
                'email' => 'nilam.susanti@student.itenas.ac.id',
                'password' => '20207250',
            ],
            [
                'student_identifier' => '202071001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@student.itenas.ac.id',
                'password' => '202071001',
            ],
            // ... data lainnya
        ];

        foreach ($students as $data) {
            Student::updateOrCreate(
                ['student_identifier' => $data['student_identifier']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]
            );
        }

        // ============================================
        // BAGIAN 2: CREATE RATINGS & REPORTS (EXTRA)
        // ============================================
        $this->createRatingsAndReports();
    }

    private function createRatingsAndReports()
    {
        $units = Unit::where('is_active', true)->get();
        $categories = RatingCategory::all();
        $students = Student::all();

        if ($units->isEmpty()) {
            $this->command->warn('⚠️ Unit tidak ada, lewati pembuatan rating');
            return;
        }

        foreach ($students as $student) {
            // 70% student punya rating
            if (rand(1, 100) <= 70) {
                $unit = $units->random();

                // Cek apakah sudah pernah rating
                if (!$student->hasRatedUnit($unit->id)) {
                    $overallScore = rand(30, 50) / 10;

                    $rating = Rating::create([
                        'tracking_code' => 'RTG-' . strtoupper(uniqid()),
                        'unit_id' => $unit->id,
                        'student_id' => $student->id,
                        'overall_score' => $overallScore,
                        'comment' => fake()->paragraph(),
                        'status' => 'active',
                    ]);

                    foreach ($categories as $category) {
                        RatingScore::create([
                            'rating_id' => $rating->id,
                            'rating_category_id' => $category->id,
                            'score' => rand(30, 50) / 10,
                        ]);
                    }
                }
            }
        }
    }
}
