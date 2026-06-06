<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingCategory;
use App\Models\Feedback\RatingScore;
use Illuminate\Database\Seeder;

class RatingScoreSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::all();
        $categories = RatingCategory::all();

        foreach ($ratings as $rating) {
            foreach ($categories as $category) {
                RatingScore::create([
                    'rating_id' => $rating->id,
                    'rating_category_id' => $category->id,
                    'score' => rand(1, 5),
                ]);
            }
        }
    }
}