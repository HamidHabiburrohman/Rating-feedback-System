<?php

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
            ],
            [
                'student_identifier' => '202071001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@student.itenas.ac.id',
                'password' => '202071001',
            ],
            [
                'student_identifier' => '202071002',
                'name' => 'Siti Aisyah',
                'email' => 'siti.aisyah@student.itenas.ac.id',
                'password' => '202071002',
            ],
            [
                'student_identifier' => '202071003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.itenas.ac.id',
                'password' => '202071003',
            ],

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

        $this->command->info('StudentSeeder selesai!');
    }
}
