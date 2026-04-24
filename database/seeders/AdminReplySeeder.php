<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\AdminReply;
use Illuminate\Database\Seeder;

class AdminReplySeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::all();

        if ($ratings->isEmpty()) {
            return;
        }

        $ratingsWithReply = $ratings->random((int)($ratings->count() * 0.3));

        foreach ($ratingsWithReply as $rating) {
            $existingReply = AdminReply::where('rating_id', $rating->id)->exists();
            
            if (!$existingReply) {
                AdminReply::factory()
                    ->forRating($rating)
                    ->create();
            }
        }
    }
}