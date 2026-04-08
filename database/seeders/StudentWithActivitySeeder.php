<?php
// database/seeders/StudentWithActivitySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Unit;
use App\Models\Rating;
use App\Models\RatingScore;
use App\Models\RatingCategory;
use Illuminate\Support\Facades\Hash;

class StudentWithActivitySeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'student_identifier' => '20207250',
                'name' => 'Nilam Susanti',
                'email' => 'nilam.susanti@student.itenas.ac.id',
                'password' => '20207250',
                'major' => 'Teknik Informatika',
                'class_year' => '2020',
                'bio' => 'Mahasiswa Teknik Informatika yang tertarik dengan pengembangan web dan mobile',
                'phone' => '081234567890',
                'location' => 'Bandung, Indonesia',
                'portfolio_url' => 'https://nilamsusanti.dev',
                'linkedin_url' => 'https://linkedin.com/in/nilamsusanti',
            ],
            [
                'student_identifier' => '202071001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@student.itenas.ac.id',
                'password' => '202071001',
                'major' => 'Teknik Sipil',
                'class_year' => '2020',
                'bio' => null,
                'phone' => null,
                'location' => null,
                'portfolio_url' => null,
                'linkedin_url' => null,
            ],
            [
                'student_identifier' => '202071002',
                'name' => 'Siti Aisyah',
                'email' => 'siti.aisyah@student.itenas.ac.id',
                'password' => '202071002',
                'major' => 'Teknik Elektro',
                'class_year' => '2020',
                'bio' => null,
                'phone' => null,
                'location' => null,
                'portfolio_url' => null,
                'linkedin_url' => null,
            ],
            [
                'student_identifier' => '202071003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.itenas.ac.id',
                'password' => '202071003',
                'major' => 'Teknik Mesin',
                'class_year' => '2020',
                'bio' => null,
                'phone' => null,
                'location' => null,
                'portfolio_url' => null,
                'linkedin_url' => null,
            ],
        ];

        foreach ($students as $data) {
            Student::updateOrCreate(
                ['student_identifier' => $data['student_identifier']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'major' => $data['major'],
                    'class_year' => $data['class_year'],
                    'bio' => $data['bio'],
                    'phone' => $data['phone'],
                    'location' => $data['location'],
                    'portfolio_url' => $data['portfolio_url'],
                    'linkedin_url' => $data['linkedin_url'],
                ]
            );
        }

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
            if (rand(1, 100) <= 70) {
                $unit = $units->random();

                if (!$student->hasRatedUnit($unit->id)) {
                    $overallScore = rand(30, 50) / 10;

                    $rating = Rating::create([
                        'tracking_code' => 'RTG-' . strtoupper(uniqid()),
                        'unit_id' => $unit->id,
                        'student_identifier' => $student->student_identifier,
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