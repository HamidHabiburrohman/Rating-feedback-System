<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\RatingCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatingScoreSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::all();
        $categories = RatingCategory::where('is_active', true)->get();

        if ($ratings->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $now = now();
        $insertData = [];

        foreach ($ratings as $rating) {
            foreach ($categories as $category) {
                $score = match ($category->slug) {
                    'facility' => fake()->randomFloat(1, 2, 5),
                    'service' => fake()->randomFloat(1, 2, 5),
                    'quality' => fake()->randomFloat(1, 2, 5),
                    default => fake()->randomFloat(1, 2, 5),
                };

                $insertData[] = [
                    'rating_id' => $rating->id,
                    'rating_category_id' => $category->id,
                    'score' => $score,
                    'created_at' => $rating->created_at ?? $now,
                    'updated_at' => $rating->created_at ?? $now,
                ];
            }
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($insertData, 500) as $chunk) {
            DB::table('rating_scores')->insert($chunk);
        }
    }
}