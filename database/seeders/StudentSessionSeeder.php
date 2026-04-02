<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentSession;
use Illuminate\Database\Seeder;

class StudentSessionSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();

        foreach ($students as $student) {
            StudentSession::factory()
                ->count(rand(1, 3))
                ->create([
                    'student_id' => $student->id,
                ]);
        }
    }
}