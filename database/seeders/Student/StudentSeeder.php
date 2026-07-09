<?php

namespace Database\Seeders\Student;

use App\Models\Authentication\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'student_identifier' => '20240001',
            'name' => 'Mahasiswa Satu',
            'email' => 'student1@itn.ac.id',
            'password' => Hash::make('password'),
            'is_active' => true,
            'major' => 'Teknik Informatika',
            'class_year' => '2024',
        ]);

        Student::create([
            'student_identifier' => '20240002',
            'name' => 'Mahasiswa Dua',
            'email' => 'student2@itn.ac.id',
            'password' => Hash::make('password'),
            'is_active' => true,
            'major' => 'Teknik Sipil',
            'class_year' => '2024',
        ]);

        Student::factory(20)->create();
    }
}