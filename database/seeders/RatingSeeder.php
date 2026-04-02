<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $students = \App\Models\Student::all();

        if ($units->isEmpty() || $students->isEmpty()) {
            return;
        }

        foreach ($units as $unit) {
            $jumlahRating = rand(3, 15);
            $selectedStudents = $students->random(min($jumlahRating, $students->count()));
            
            foreach ($selectedStudents as $student) {
                $ratingExist = Rating::where('unit_id', $unit->id)
                    ->where('student_id', $student->id)
                    ->exists();
                
                if (!$ratingExist) {
                    Rating::factory()
                        ->forUnit($unit)
                        ->byStudent($student)
                        ->create();
                }
            }
        }
    }
}