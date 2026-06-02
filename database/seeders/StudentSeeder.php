<?php
// database/seeders/StudentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
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
                'photo' => null,
            ],
            [
                'student_identifier' => '2324102082',
                'name' => 'Hamid Habiburrohman',
                'email' => 'wulanzunet@gmail.com',
                'password' => '2324102082',
                'major' => 'Teknik Informatika',
                'class_year' => '2020',
                'bio' => 'Mahasiswa Teknik Informatika yang tertarik dengan pengembangan web dan mobile',
                'phone' => '089637334694',
                'location' => 'Bandung, Indonesia',
                'portfolio_url' => 'https://hamidhabiburrohman.dev',
                'linkedin_url' => 'https://linkedin.com/in/hamidhabiburrohman',
                'photo' => null,
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
                    'photo' => $data['photo'],
                ]
            );
        }

        $this->command->info('StudentSeeder selesai!');
    }
}
