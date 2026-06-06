<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingReply;
use App\Models\Authentication\Employee;
use Illuminate\Database\Seeder;

class RatingReplySeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::inRandomOrder()->limit(30)->get();
        $employees = Employee::all();

        foreach ($ratings as $rating) {
            if (rand(0, 1)) {
                RatingReply::factory()
                    ->forRating($rating)
                    ->byEmployee($employees->random())
                    ->create();
            }
        }
    }
}